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
    name: 'app:generate-game-categories-and-enums',
    description: 'loops through the game jsons and gets every single category and genres, and puts both in their respective enum files',
    aliases: ['app:gen-cat-genre-data'],
    hidden: false
)]
class GenerateCategoriesAndGenres extends Command
{
    private FilestructureHelper $filestructureHelper;
    private Filesystem $filesystem;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filestructureHelper = new FilestructureHelper();
        $this->filesystem = new Filesystem();
        $collectedGenres = [];
        $collectedCategories = [];

        foreach ($this->filestructureHelper->getAppIds() as $appId) {
            $appId = $appId[1];
            printf("\n processing {$appId} \n");

            $json = json_decode(file_get_contents("assets/steam/games/{$appId}/{$appId}.json"), true);

            $collectedCategories = $this->collect($json, $appId, $collectedCategories, "categories");
            $collectedGenres = $this->collect($json, $appId, $collectedGenres, "genres");
        }

        $categories = json_encode($collectedCategories);
        $genres = json_encode($collectedGenres);

        $this->filesystem->dumpFile("assets/steam/categories.json", $categories);
        $this->filesystem->dumpFile("assets/steam/genres.json", $genres);

        printf("dumped categories.json and genres.json @/assets/steam/");

        return Command::SUCCESS;
    }

    protected function collect($json, $appId, $collected, $string): array
    {
        if(array_key_exists($string, $json[$appId]["data"])) {
            $things = $json[$appId]["data"][$string];
            foreach ($things as $thing) {
                if(!in_array($thing, $collected)) {
                    $collected[] = $thing;
                }
            }
        }

        return $collected;
    }
}
