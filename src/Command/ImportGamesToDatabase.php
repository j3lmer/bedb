<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
use App\Repository\GameRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:import-games-to-db',
    description: 'imports the local json files to the db',
    aliases: ["app:import-games"],
    hidden: false
)]
class ImportGamesToDatabase extends Command
{
    private Filesystem $filesystem;
    private FilestructureHelper $filestructureHelper;
    private string $logPath = "assets/steam/logs/import.txt";
    private GameRepository $gameRepository;
    private string $localServer = "http://127.0.0.1:8000/";

    //TODO: iets maken wat er voor zorgt dat genres (en categorieen) niet allemaal los in de db zitten


    public function __construct(GameRepository $siteDataRepository)
    {
        parent::__construct();
        $this->gameRepository = $siteDataRepository;
        $this->filesystem = new Filesystem();
        $this->filestructureHelper = new FilestructureHelper();
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ids = $this->filestructureHelper->getAppIds();
        $this->createLogFile();

        foreach ($ids as $id) {
            $id = $id[1];
            $path = "assets/steam/games/{$id}/{$id}.json";
            $data = json_decode(file_get_contents($path), true);

            if($this->gameRepository->find($id) !== null) {
                printf("\n Game with id {$id} is already in database, skipping");
                continue;
            }

            if (!array_key_exists($id, $data)) {
                $this->appendToLogfile("\n key {$id} does not exist on {$id}.json \n");
                continue;
            }
            if (!array_key_exists("data", $data[$id])) {
                $this->appendToLogfile("\n key 'data' does not exist on {$id}.json \n");
                continue;
            }
            $data = $this->configureData($data, $id);
            printf("\n posting {$id} to internal api.. \n");
            $this->trySend($data, $id);
        }
        return Command::SUCCESS;
    }

    protected function trySend(array $data, string $id): void
    {
        try {
            $this->postToApi($data);
        } catch (ClientExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data for id {$id} to database. ClientException \n");
        } catch (RedirectionExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data for id {$id} to database. RedirectionException \n");
        } catch (ServerExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data for id {$id} to database. ServerException \n");
        } catch (TransportExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data for id {$id} to database. TransportException \n");
        }
    }

    protected function configureData(array $data, string $id): array
    {
        $data = $data[$id]["data"];
        $data = $this->replaceKeys('steam_appid', 'id', $data);
        unset($data["reviews"]);

        if(!array_key_exists('genres', $data)) {
            return $data;
        }

        for ($i = 0; $i < count($data["genres"]); $i++) {
            $data["genres"][$i]["id"] = (int)$data["genres"][$i]["id"];
        }

        $data = $this->replaceKeys('detailed_description', 'detailedDescription', $data);
        $data = $this->replaceKeys('short_description', 'shortDescription', $data);
        $data = $this->replaceKeys('about_the_game', 'about', $data);
        $data = $this->replaceKeys('required_age', 'nsfw', $data);
        $data = $this->replaceKeys('platforms', 'platform', $data);
        $data = $this->replaceKeys('platforms', 'platform', $data);
        $data = $this->replaceKeys('path_thumbnail', 'thumbnail', $data);
        $data = $this->replaceKeys('path_full', 'full', $data);
        $data = $this->replaceKeys('pc_requirements', 'pc_requirement', $data);

        /**
         * possible useful resources:
         * https://stackoverflow.com/questions/64661753/api-platform-post-embedded-document-in-manytomany-relation-using-id-not-iris
         * https://symfonycasts.com/screencast/api-platform/embedded
         */
        $data["nsfw"] = $data["nsfw"] >= 18;
        $data["pc_requirement"]["game"] =  "/api/games/" . $id;
        $data["platform"]["game"] =  "/api/games/" . $id;
        $data["metacritic"]["game"] =  "/api/games/" . $id;
        $data["release_date"]["game"] =  "/api/games/" . $id;
        $data["screenshots"]["game"] =  "/api/games/" . $id;

        return $data;
    }

    protected function createLogFile(): void
    {
        $this->filesystem->dumpFile($this->logPath, '');
    }

    protected function appendToLogfile(string $text): void
    {
        $this->filesystem->appendToFile($this->logPath, $text);
    }

    protected function replaceKeys($oldKey, $newKey, array $input): array
    {
        $return = [];
        foreach ($input as $key => $value) {
            if ($key === $oldKey)
                $key = $newKey;

            if (is_array($value))
                $value = $this->replaceKeys($oldKey, $newKey, $value);

            $return[$key] = $value;
        }
        return $return;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function postToApi(array $data): void
    {
        dd(json_encode($data));
        $client = HttpClient::create();

        $response = $client->request('POST', $this->localServer . "api/games", [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);

        if ($response->getStatusCode() > 299) {
            $this->appendToLogfile("\n api call to post game failed, content: {$response->getContent()} \n");
        }

    }

}