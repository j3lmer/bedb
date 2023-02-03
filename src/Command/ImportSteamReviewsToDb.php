<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
use App\Repository\SteamReviewRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:import-steam-reviews-to-db',
    description: 'loops through all of the collected steam reviews, and imports them one by one to the database',
    aliases: ['app:gen-steam-rev'],
    hidden: false
)]
class ImportSteamReviewsToDb extends Command
{
    private FilestructureHelper $filestructureHelper;
    private Filesystem $filesystem;
    private SteamReviewRepository $steamReviewRepository;


    public function __construct(SteamReviewRepository $steamReviewRepository)
    {
        parent::__construct();
        $this->steamReviewRepository = $steamReviewRepository;
        $this->filesystem = new Filesystem();
        $this->filestructureHelper = new FilestructureHelper();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->filestructureHelper->getAppIds() as $appId) {
            $path = "assets/steam/games/{$appId}/{$appId}_reviews.json";

            if (!file_exists($path)) {
                printf("{$appId} No review file found, skipping.. \n \n");
                continue;
            }
            $json = json_decode(file_get_contents($path), true);

            if ($json === "[[]]" || $json === [[]]) {
                printf("{$appId} has no reviews, skipping.. \n \n");
                continue;
            }

            $reviewId = $json[0][0]["recommendationid"];

            if($this->steamReviewRepository->find($reviewId) !== null) {
                printf("{$reviewId} already in database, skipping.. \n \n");
                
                continue;
            }
        }


        return Command::SUCCESS;
    }
}
