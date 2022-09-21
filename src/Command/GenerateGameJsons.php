<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

        $package = new Package(new EmptyVersionStrategy());
        $path = $package->getUrl('assets/steam/steamgames.json');
        $data = file_get_contents($path);
        $json = json_decode($data, true);

        $gameList = $json["applist"]["apps"];

        for ($i = 0; $i < 1; $i++) { //temporary code
            $response = file_get_contents("https://store.steampowered.com/api/appdetails?appids={$gameList[$i]["appid"]}");
            
        }

        return Command::SUCCESS;
    }
}
