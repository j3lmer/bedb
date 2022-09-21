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

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:generate-game-jsons',
    description: 'Creates files with steam appids as name with the respective appdetails in them in json form.',
    aliases: ['app:gen-games'],
    hidden: false
)]
class GenerateGameJsons extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootDir = '/home/j3lmer/projects/GitHub/bedb'; //beetje vieze code, lukte zo snel niet om te injecteren in een Command.
        $filesystem = new Filesystem();


        $package = new Package(new EmptyVersionStrategy());
        $json = json_decode(
            file_get_contents($package->getUrl('assets/steam/steamgames.json')),
            true
        );

        $gameList = $json["applist"]["apps"];

        for ($i = 0; $i < count($gameList); $i++) {
            $appId = $gameList[$i]["appid"];
            $path = "{$rootDir}/assets/steam/games/{$appId}.json";
            $response = file_get_contents("https://store.steampowered.com/api/appdetails?appids={$appId}");

            $filesystem->dumpFile($path, $response);
            printf("dumped file {$appId}.json for game {$gameList[$i]["name"]} \n");
        }

        return Command::SUCCESS;
    }
}
