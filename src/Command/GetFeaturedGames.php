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
    name: 'app:get-featured-games',
    description: 'gets the reviews for all games currently in filesystem',
    aliases: ["app:get-featured"],
    hidden: false
)]
class GetFeaturedGames extends Command
{
//TODO: this command has to be run weekly

    private Filesystem $filesystem;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->filesystem = new Filesystem();
        $featuredWinIds = $this->getFeaturedGameIds();
        $this->filesystem->dumpFile("assets/steam/featuredGames.json", json_encode($featuredWinIds));
        return Command::SUCCESS;
    }

    protected function getFeaturedGameIds(): array
    {
        $featured = json_decode(file_get_contents("https://store.steampowered.com/api/featured/"), true);
        $featuredWinIds = [];
        foreach ($featured["featured_win"] as $featuredGame) {
            $featuredWinIds[] = $featuredGame["id"];
        }

        return $featuredWinIds;
    }


}