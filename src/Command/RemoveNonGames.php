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
    name: 'app:remove-non-games',
    description: 'Removes all folders and files where the appid is not a game or has no data at all',
    aliases: ['app:rem-non-games'],
    hidden: false
)]
class RemoveNonGames extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filestructureHelper = new FilestructureHelper();
        $filesystem = new Filesystem();
        $appIds = $filestructureHelper->getAppIds();
        $pathPrefix = 'assets/steam/games/';

        foreach ($appIds as $appId) {
            if (!$filesystem->exists("{$pathPrefix}/{$appId}/{$appId}.json")) {
                printf("No json found for " . $appId . ". Removing.. \n");
                $filesystem->remove("{$pathPrefix}/{$appId}");
            }

            $file = json_decode(file_get_contents("{$pathPrefix}/{$appId}/{$appId}.json"), true);
            $type = $this->getType($file, $appId);


            if ($type === null) {
                printf($appId . " has no type, removing.. \n");
                $this->removeGame($filesystem, "{$pathPrefix}/{$appId}");
            } else if ($type !== "game") {
                printf($appId . " is not a game, removing.. \n");
                $this->removeGame($filesystem, "{$pathPrefix}/{$appId}");
            }

            printf($appId . " is a game, skipping.. \n");
        }
        return Command::SUCCESS;
    }

    protected function getType($file, $appId): string|null
    {
        if (!isset($file) || !isset($file[$appId]) || !isset($file[$appId]["data"]) || !isset($file[$appId]["data"]["type"])) {
            return null;
        }
        return $file[$appId]["data"]["type"];
    }

    protected function removeGame(Filesystem $filesystem, string $path): void
    {
        $filesystem->remove("{$path}");
    }
}
