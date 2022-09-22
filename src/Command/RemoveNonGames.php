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
    private FilestructureHelper $filestructureHelper;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filestructureHelper = new FilestructureHelper();
        $filesystem = new Filesystem();

        $paths = $this->filestructureHelper->getGamePaths();

        foreach ($paths as $path) {
            $appId = explode('assets/steam/games/', $path)[1];
            $file = json_decode(file_get_contents("{$path}/{$appId}.json"), true);

            $type = $this->getType($file, $appId);

            if ($type === null) {
                printf($appId . " has no type \n");
                $this->removeGame($filesystem, $path);
            } else if ($type !== "game") {
                printf($appId . " is not a game \n");
                $this->removeGame($filesystem, $path);
            }
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
