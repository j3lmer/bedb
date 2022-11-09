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
        [$genres, $categories] = $this->getCategoriesAndGenres();

        $this->filesystem->dumpFile("assets/steam/categories.json", json_encode($categories));
        $this->filesystem->dumpFile("assets/steam/genres.json", json_encode($genres));

        printf("dumped categories.json and genres.json @/assets/steam/");

        return Command::SUCCESS;
    }


    protected function getCategoriesAndGenres(): array
    {
        $this->filestructureHelper = new FilestructureHelper();
        $this->filesystem = new Filesystem();
        $collectedGenres = [];
        $collectedCategories = [];

        foreach ($this->filestructureHelper->getAppIds() as $appId) {
            printf("\n processing {$appId} \n");

            $json = json_decode(file_get_contents("assets/steam/games/{$appId}/{$appId}.json"), true);

            $collectedCategories = $this->collect($json, $appId, $collectedCategories, "categories");
            $collectedGenres = $this->collect($json, $appId, $collectedGenres, "genres");
        }

        return [$collectedGenres, $collectedCategories];
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
