<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
use App\Entity\Game;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\GenresRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
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
    private GenresRepository $genresRepository;
    private CategoryRepository $categoryRepository;
    private string $localServer = "http://127.0.0.1:8000/";

    //TODO: game en alle andere entities beheren qua access e.d.


    public function __construct(GameRepository $gameRepository, GenresRepository $genresRepository, CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->gameRepository = $gameRepository;
        $this->genresRepository = $genresRepository;
        $this->categoryRepository = $categoryRepository;
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
            $path = "assets/steam/games/{$id}/{$id}.json";
            $data = json_decode(file_get_contents($path), true);

            //TODO: checken of alle values die er bij horen ook te vinden zijn
            if ($this->gameRepository->find($id) !== null) {
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
            $truncatedData = $this->getTruncatedData($data);
            $game = $this->trySend($truncatedData, $this->localServer . "api/games", $id);

            if (!isset($game["id"])) {
                $this->appendToLogfile("Game was sent but did not come back correctly for id " . $id);
                continue;
            }
            $game = $this->gameRepository->find($game["id"]);

            printf("\n sent game {$id}, handling subEntities. \n");

            $this->handleSubEntities($data, $id, $game);
        }
        return Command::SUCCESS;
    }


    protected function handleSubEntities(array $data, string $id, Game $game)
    {
        $genres = $this->getSubEntity('genres', $data, $id);
        $categories = $this->getSubEntity('categories', $data, $id);
        $screenshots = $this->getSubEntity('screenshots', $data, $id);
        $release_date = $this->getSubEntity('release_date', $data, $id);
        $metacritic = $this->getSubEntity('metacritic', $data, $id);
        $platform = $this->getSubEntity('platform', $data, $id);
        $pc_requirement = $this->getSubEntity('pc_requirement', $data, $id);

        $this->trySend($release_date, $this->localServer . "api/release_dates", $id);
        $this->trySend($metacritic, $this->localServer . "api/metacritics", $id);
        $this->trySend($platform, $this->localServer . "api/platforms", $id);
        $this->trySend($pc_requirement, $this->localServer . "api/pc_requirements", $id);

        foreach ($genres as $genre) {
            $possibleExisingGenre = $this->genresRepository->findOneByDescription($genre["description"]);
            if ($possibleExisingGenre !== null) {
                $possibleExisingGenre->addGame($game);
                $this->genresRepository->save($possibleExisingGenre, true);
            } else {
                $this->trySend($genre, $this->localServer . "api/genres", $id);
            }
        }

        foreach ($categories as $category) {
            $possibleExisingCategory = $this->categoryRepository->findOneByDescription($category["description"]);
            if ($possibleExisingCategory !== null) {
                $possibleExisingCategory->addGame($game);
                $this->categoryRepository->save($possibleExisingCategory, true);
            } else {
                $this->trySend($category, $this->localServer . "api/categories", $id);
            }
        }
        unset($screenshots["game"]);
        foreach ($screenshots as $screenshot) {
            $this->trySend($screenshot, $this->localServer . "api/screenshots", $id);
        }
    }

    protected function getSubEntity(string $entityKeyName, array $data, string $id): array
    {
        $return = [];
        foreach ($data as $key => $value) {
            if ($key === $entityKeyName) {
                return $value;
            }
        }
        $this->appendToLogfile("was not able to find key {$entityKeyName} in {$id}");
        return $return;
    }

    protected function configureData(array $data, string $id): array
    {
        $data = $data[$id]["data"];
        $data = $this->replaceKeys('steam_appid', 'id', $data);
        unset($data["reviews"]);

        if (!array_key_exists('genres', $data)) {
            return $data;
        }

        for ($i = 0; $i < count($data["genres"]); $i++) {
            $data["genres"][$i]["id"] = (int)$data["genres"][$i]["id"];
        }

        $data = $this->replaceKeys('detailed_description', 'detailedDescription', $data);
        $data = $this->replaceKeys('supported_languages', 'supportedLanguages', $data);
        $data = $this->replaceKeys('short_description', 'shortDescription', $data);
        $data = $this->replaceKeys('about_the_game', 'about', $data);
        $data = $this->replaceKeys('required_age', 'nsfw', $data);
        $data = $this->replaceKeys('platforms', 'platform', $data);
        $data = $this->replaceKeys('platforms', 'platform', $data);
        $data = $this->replaceKeys('path_thumbnail', 'thumbnail', $data);
        $data = $this->replaceKeys('path_full', 'full', $data);
        $data = $this->replaceKeys('pc_requirements', 'pc_requirement', $data);
        $data = $this->replaceKeys('header_image', 'headerImage', $data);
        $data = $this->replaceKeys('coming_soon', 'comingSoon', $data);
        $data["nsfw"] = $data["nsfw"] >= 18;
        $data["pc_requirement"]["game"] = "api/games/" . $id;
        $data["platform"]["game"] = "api/games/" . $id;
        $data["metacritic"]["game"] = "api/games/" . $id;
        $data["release_date"]["game"] = "api/games/" . $id;
        $data["screenshots"]["game"] = "api/games/" . $id;
        if (array_key_exists("content_descriptors", $data) && array_key_exists("notes", $data["content_descriptors"])) {
            $data["notes"] = $data["content_descriptors"]["notes"];
        }

        $out = max(
            (max(
                count($data["genres"]),
                count($data["categories"] ?? $data["screenshots"])
            )),
            count($data["screenshots"])
        );

        for ($i = 0; $i < $out; $i++) {
            if (isset($data["genres"][$i])) {
                $data["genres"][$i]["games"] = ["api/games/" . $id];
            }
            if (isset($data["categories"][$i])) {
                $data["categories"][$i]["games"] = ["api/games/" . $id];
            }
            if (isset($data["screenshots"][$i])) {
                $data["screenshots"][$i]["game"] = "api/games/" . $id;
            }
        }
        return $data;
    }

    protected function trySend(array $data, string $endpoint, string $id): array|null
    {
        try {
            return $this->postToApi($data, $endpoint);
        } catch (ClientExceptionInterface $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            $this->appendToLogfile("\n could not post data" . json_encode($data) . "for id {$id} to database. ClientException" . $code . $message . "\n");
        } catch (RedirectionExceptionInterface $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            $this->appendToLogfile("\n could not post data" . json_encode($data) . "for id {$id} to database. RedirectionExceptionInterface" . $code . $message . "\n");
        } catch (ServerExceptionInterface $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            $this->appendToLogfile("\n could not post data" . json_encode($data) . "for id {$id} to database. ServerExceptionInterface" . $code . $message . "\n");
        } catch (TransportExceptionInterface $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            $this->appendToLogfile("\n could not post data" . json_encode($data) . "for id {$id} to database. TransportExceptionInterface" . $code . $message . "\n");
        } catch (DecodingExceptionInterface $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            $this->appendToLogfile("\n could not post data" . json_encode($data) . "for id {$id} to database. DecodingExceptionInterface" . $code . $message . "\n");
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    protected function postToApi(array $data, string $url): array
    {
        $client = HttpClient::create();

        $response = $client->request('POST', $url, [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => $data,
        ]);

        if ($response->getStatusCode() > 299) {
            $this->appendToLogfile("\n api call to post failed, content: {$response->getContent()} \n");
        }
        return $response->toArray();
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


    protected function getTruncatedData(array $data): array
    {
        $subEntities = ['genres', 'categories', 'screenshots', 'release_date', 'metacritic', 'platform', 'pc_requirement'];

        foreach ($data as $key => $value) {
            if (in_array($key, $subEntities)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

}