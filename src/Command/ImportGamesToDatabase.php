<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
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
    //TODO: currently not functional, try to make all sub entities of the game api accessible, then first post all of the sub entities and then the game. or maybe look into this page some more: https://api-platform.com/docs/core/serialization/#using-serialization-groups


    private Filesystem $filesystem;
    private FilestructureHelper $filestructureHelper;
    private string $logPath = "assets/steam/logs/import.txt";

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filesystem = new Filesystem();
        $this->filestructureHelper = new FilestructureHelper();

        $ids = $this->filestructureHelper->getAppIds();
        $this->createLogFile();

        foreach ($ids as $id) {
            $id = $id[1];
            $path = "assets/steam/games/{$id}/{$id}.json";
            $data = json_decode(file_get_contents($path), true);

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
            $this->trySend($data);
        }
        return Command::SUCCESS;
    }

    protected function trySend(array $data): void
    {
        try {
            $this->postToApi($data);
        } catch (ClientExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data to database. ClientException \n");
        } catch (RedirectionExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data to database. RedirectionException \n");
        } catch (ServerExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data to database. ServerException \n");
        } catch (TransportExceptionInterface $e) {
            $this->appendToLogfile("\n could not post game data to database. TransportException \n");
        }
    }

    protected function configureData(array $data, int $id): array
    {
        $data = $data[$id]["data"];
        $data = $this->replaceKeys('steam_appid', 'id', $data);
        unset($data["reviews"]);

        for ($i = 0; $i < count($data["genres"]); $i++) {
            $data["genres"][$i]["id"] = (int)$data["genres"][$i]["id"];
        }

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
        $client = HttpClient::create();

        $response = $client->request('POST', "http://127.0.0.1:8000/api/games", [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);

        if ($response->getStatusCode() > 299) {
            $this->appendToLogfile("\n api call to post game failed, content: {$response->getContent()} \n");
        }
    }

}