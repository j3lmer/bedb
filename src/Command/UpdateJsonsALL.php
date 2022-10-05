<?php
declare(strict_types=1);

namespace App\Command;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-jsons-all',
    description: 'Runs all commands in the correct order to get the whole application ready.',
    aliases: ['app:update-all'],
    hidden: false
)]
class UpdateJsonsALL extends Command
{

    /**
     * @throws Exception|ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getApplication()->find('app:generate-game-jsons')->run($input, $output);
        $this->getApplication()->find("app:remove-non-games")->run($input, $output);
        $this->getApplication()->find('app:get-reviews')->run($input, $output);
        $this->getApplication()->find("app:generate-game-enums")->run($input, $output);
        $this->getApplication()->find("app:get-featured-games")->run($input, $output);
        $this->getApplication()->find("app:import-games-to-db")->run($input, $output);
        return Command::SUCCESS;
    }
}
