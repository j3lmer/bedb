<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
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

// Ik kan alles van tevoren in mijn eigen db zetten, en dan per zoveel geven (queryen) aan de browser

//TODO: ZET DIT IN FLOWCHART
// Ik kan dynamisch de steam api queryen bij pagina load, en het dan op de achtergrond opslaan naar mijn db,
// en het dan in de toekomst uit mn eigen db halen


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filestructureHelper = new FilestructureHelper();
        $filesystem = new Filesystem();
        $numReviewsPerChunk = 100; // max 100
        $numTotalReviewsPerGame = 100;

        $this->getReviews($filesystem, $numReviewsPerChunk, $numTotalReviewsPerGame);
        return Command::SUCCESS;
    }


    protected function getReviews(Filesystem $filesystem, int $numReviewsPerChunk, $numTotalReviewsPerGame) // only for test purposes
    {
        $appids = $this->filestructureHelper->getAppIds();
        $cursor = '*';

        foreach ($appids as $appid) {
            $reviewCounter = 0;
            $reviewPath = "assets/steam/games/{$appid}/{$appid}_reviews.json";

            printf("\n Deleting reviews and creating empty file for: {$appid} \n");
            $this->removePreExistingReviews($reviewPath, $filesystem);

            printf("\n Getting reviews for: {$appid} \n");
            while (isset($cursor) && $cursor !== '') {
                $apiString = "https://store.steampowered.com/appreviews/{$appid}?json=1&num_per_page={$numReviewsPerChunk}&review_type=all&cursor={$cursor}";
                $json = json_decode(file_get_contents($apiString), true);
                $json["appid"] = $appid;

                if (!array_key_exists("cursor", $json)) {
                    printf("\n Cursor not found, skipping \n");
                    break;
                }

                $this->appendToReviewFile($json, $reviewPath, $filesystem);
                $cursor = urlencode($json["cursor"]);
                $reviewCounter += $numReviewsPerChunk;
                if ($reviewCounter >= $numTotalReviewsPerGame) {
                    printf("\n Got enough reviews, skipping to next game \n");
                    break;
                }
            }
            $filesystem->dumpFile(
                $reviewPath,
                substr(
                    file_get_contents($reviewPath),
                    0,
                    -1
                ) . ']'
            );
            printf("\n \n");
        }
    }

    protected function removePreExistingReviews(string $reviewPath, Filesystem $filesystem)
    {
        if ($filesystem->exists($reviewPath)) {
            $filesystem->remove($reviewPath);
        }
        $filesystem->dumpFile($reviewPath, '');
        printf("\n Created new empty file \n");
    }

    protected function appendToReviewFile($json, string $reviewPath, Filesystem $filesystem)
    {
        $appid = $json["appid"];

        $filesystem->appendToFile($reviewPath, '[');
        $filesystem->appendToFile($reviewPath, json_encode($json["reviews"]));
        $filesystem->appendToFile($reviewPath, ',');
        printf("\n Filled reviewfile of {$appid} \n");
    }
}