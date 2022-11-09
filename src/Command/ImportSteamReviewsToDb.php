<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Helper\FilestructureHelper;
use DirectoryIterator;
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filestructureHelper = new FilestructureHelper();
        $this->filesystem = new Filesystem();




        foreach ($this->filestructureHelper->getAppIds() as $appId) {


            $json = json_decode(file_get_contents("assets/steam/games/{$appId}/{$appId}_reviews.json"), true);

            dd("assets/steam/games/{$appId}/{$appId}_reviews.json");
            dd($json);

        }
        return Command::SUCCESS;
    }
}
