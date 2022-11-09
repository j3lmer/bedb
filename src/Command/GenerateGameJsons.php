<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:generate-game-jsons',
    description: 'Gets updated game list from steam and creates files with steam appids as name with the respective appdetails in them in json form.',
    aliases: ['app:gen-games'],
    hidden: false
)]
class GenerateGameJsons extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $package = new Package(new EmptyVersionStrategy());
        $this->getUpdatedGameList($filesystem, $package);
        $json = json_decode(
            file_get_contents($package->getUrl('assets/steam/steamgames.json')),
            true
        );
        $gameList = $json["applist"]["apps"];

        $this->dumpGames($filesystem, $package, $gameList);
        return Command::SUCCESS;
    }

    protected function getUpdatedGameList(Filesystem $filesystem, Package $package)
    {
        $path = "{$package->getUrl("assets/steam/")}steamgames.json";
        $response = file_get_contents("https://api.steampowered.com/ISteamApps/GetAppList/v0002/?key=STEAMKEY&format=json");
        $filesystem->dumpFile($path, $response);
        printf("Got updated games list!\n");
    }

    protected function dumpGames(Filesystem $filesystem, Package $package, mixed $gameList)
    {
        $sleepCounter = 0;
        for ($i = 0; $i < count($gameList); $i++) {
            $appId = $gameList[$i]["appid"];
            $path = "{$package->getUrl("assets/steam/games/")}/{$appId}/{$appId}.json";

            if($filesystem->exists("{$package->getUrl("assets/steam/games/")}/{$appId}/{$appId}.json")) { // TODO: een soort switch maken dat dit alleen gebeurt als ik t wil
                printf("json file already exists for {$appId}, skipping.. \n \n");
                continue;
            }

            $response = file_get_contents("https://store.steampowered.com/api/appdetails?appids={$appId}");

            $filesystem->dumpFile($path, $response);
            printf("Dumped file {$appId}.json for game: {$gameList[$i]["name"]} \n");
            $sleepCounter++;

            if ($sleepCounter === 190) {
                $sleepCounter = 0;
                printf("Sleeping for 5 min to avoid too many requests error. \n");
                sleep(300);
            }
        }
    }
}
