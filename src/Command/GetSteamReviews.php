<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
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
    hidden: false
)]
class GetSteamReviews extends Command
{

    private FilestructureHelper $filestructureHelper;

//TODO: https://symfony.com/doc/current/console.html
// Gebruik deze link om bij command executie een cijfer in te nemen van hoeveel review chunks er moeten worden opgehaald

//Opties:
// Ik kan op pagina load van een gebruiker, de steam api callen en dan dat zo displayen,
// en dan de cursor incrementeren als de gebruiker meer dan 20 reviews wil zien
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

//TODO: Command maken die alle non-games verwijderd


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filestructureHelper = new FilestructureHelper();
        $filesystem = new Filesystem();
        $numReviewsPerChunk = 25; // max 100
        $numTotalReviewsPerGame = 50;

        $this->getReviews($filesystem, $numReviewsPerChunk, $numTotalReviewsPerGame);

        return Command::SUCCESS;
    }

    protected function getAppIds(): array
    {
        $appids = [];

        foreach ($this->filestructureHelper->getGamePaths() as $path) {
            $appids[] = explode("assets/steam/games/", $path);
        }
        return $appids;
    }

    protected function getReviews(Filesystem $filesystem, int $numReviewsPerChunk, $numTotalReviewsPerGame) // only for test purposes
    {
        $appids = $this->getAppIds();
        $cursor = '*';

        foreach ($appids as $appid) {
            $reviewCounter = 0;
            $appid = $appid[1];

            printf("\n Reviews verwijderen en leeg bestand maken voor voor: {$appid} \n");
            $this->removePreExistingReviews($appid, $filesystem);

            printf("\n Reviews ophalen voor: {$appid} \n");
            while (isset($cursor) && $cursor !== '') {
                $api_string = "https://store.steampowered.com/appreviews/{$appid}?json=1&num_per_page={$numReviewsPerChunk}&review_type=all&cursor={$cursor}";
                $json = json_decode(file_get_contents($api_string),true);

                $json["appid"] = $appid;

                if (!array_key_exists("cursor", $json)) {
                    printf("\n Cursor not found, skipping \n");
                    break;
                }

                $this->appendToReviewFile($json, $filesystem);
                $cursor = urlencode($json["cursor"]);

                $reviewCounter += $numReviewsPerChunk;
                if ($reviewCounter >= $numTotalReviewsPerGame) {
                    printf("\n Got enough reviews, skipping to next game \n");
                    break;
                }
            }

            printf("\n \n");
        }
    }

    protected function removePreExistingReviews($appid, Filesystem $filesystem)
    {
        $reviewPath = "assets/steam/games/{$appid}/{$appid}_reviews.json";
        if ($filesystem->exists($reviewPath)) {
            $filesystem->remove($reviewPath);
        }
        $filesystem->dumpFile($reviewPath, '');

        printf("\n Created new empty file \n");
    }

    protected function appendToReviewFile($json, Filesystem $filesystem)
    {
        $appid = $json["appid"];
        $reviewPath = "assets/steam/games/{$appid}/{$appid}_reviews.json";

        $filesystem->appendToFile($reviewPath, json_encode($json["reviews"]));
        printf("\n Filled reviewfile of {$appid} \n");
    }
}