<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:calulate-user-scores',
    description: 'loops through all games with reviews, calculates user scores',
    aliases: ['app:calc-scores'],
    hidden: false
)]
class CalculateUserScores extends Command
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        parent::__construct();
        $this->gameRepository = $gameRepository;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allGames = $this->gameRepository->findAll();
        for ($i = 0; $i < count($allGames); $i++) {
            $thisGame = $allGames[$i];
            $reviews = $thisGame->getReviews()->toArray();
            if (count($reviews) > 0) {
                $thisGame->setUserScore($this->calculateUserScore($thisGame));
            }
        }

        return Command::SUCCESS;
    }

    private function calculateUserScore(Game $game): int
    {

    }
}

