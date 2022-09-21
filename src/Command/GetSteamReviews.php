<?php

namespace App\Command;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:get-reviews',
    description: 'gets the reviews for all games currently in filesystem',
    aliases: ['app:gen-games'],
    hidden: false
)]
class GetSteamReviews extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $package = new Package(new EmptyVersionStrategy());

        $this->getReviews($package);

        return Command::SUCCESS;
    }
//todo: https://symfony.com/doc/current/console.html
// Gebruik deze link om bij command executie een cijfer in te nemen van hoeveel reviewchunks er moeten worden opgehaald

//Opties:
// Ik kan op pagina load van een gebruiker, de steam api callen en dan dat zo displayen,
// en dan de cursor incrementen als de gebruiker meer dan 20 reviews wil zien
// Voordelen:
// Nadelen:

// Ik kan alles van tevoren in mijn eigen db zetten, en dan per zoveel geven (queryen) aan de browser
// Voordelen:
// Nadelen:

//TODO: ZET DIT IN HET FUNCTIONEEL ONTWERP + FLOWCHART
// Ik kan dynamisch de steam api queryen bij pagina load, en het dan op de achtergrond opslaan naar mijn db,
// en het dan in de toekomst uit mn eigen db halen
// Voordelen:
// Nadelen:

    protected function getReviews(Package $package, Filesystem $filesystem) //deprecated
    {
        $appids = [];
        $cursor = '*';
        $reviewChunks = [];
        $path = $package->getUrl("assets/steam/games/");

        foreach (glob("{$package->getUrl("assets/steam/games/**")}") as $path) {
            $appids[] = explode("assets/steam/games/", $path, 1000000);
        }


        foreach($appids as $appid) {
            printf("Reviews ophalen voor: {$appid[1]} \n");
            while(isset($cursor) && $cursor !== '') {
                $json = json_decode(
                    file_get_contents(
                        "https://store.steampowered.com/appreviews/{$appid[1]}?json=1&num_per_page=100&review_type=all&cursor={$cursor}"),
                    true
                );
                $reviewChunks[] = $json;
                $cursor = $json["cursor"];
            }

        }

        foreach($reviewChunks as $reviewChunk) {
            foreach($reviewChunk as $review) {
//                $filesystem->exists()
                var_dump($review);
            }
        }

    }
}