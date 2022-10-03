<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-jsons-all',
    description: 'Runs the generate game jsons command, then the remove non games command, and lastly runs get steam reviews command.',
    aliases: ['app:update-all'],
    hidden: false
)]
class UpdateJsonsALL extends Command
{

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $getGames = $this->getApplication()->find('app:generate-game-jsons');
        $getGames->run($input, $output);
        $removeNonGames = $this->getApplication()->find("app:remove-non-games");
        $removeNonGames->run($input, $output);
        $getReviews = $this->getApplication()->find('app:get-reviews');
        $getReviews->run($input, $output);
        $generateEnums = $this->getApplication()->find("app:generate-game-enums");
        $generateEnums->run($input, $output);
        $getFeaturedGames = $this->getApplication()->find("app:get-featured-games");
        $getFeaturedGames->run($input, $output);

        return Command::SUCCESS;

    }
}
