<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Review;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:calulate-user-scores',
    description: 'loops through all games with reviews, calculates user scores',
    aliases: ['app:calc-scores'],
    hidden: false
)]
class CalculateUserScores extends Command
{
    private GameRepository $gameRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(GameRepository $gameRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->gameRepository = $gameRepository;
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allGames = $this->gameRepository->findAll();
        for ($i = 0; $i < count($allGames); $i++) {
            $thisGame = $allGames[$i];
            $reviews = $thisGame->getReviews()->toArray();
            if (count($reviews) > 0) {
                $score = $this->calculateUserScore($reviews);
                $thisGame->setUserScore($score);
                $this->entityManager->persist($thisGame);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }

    /**
     * @param Review[] $reviews
     * @return int
     */
    private function calculateUserScore(array $reviews): int
    {
        $scores = [];
        for ($i = 0; $i < count($reviews); $i++) {
            $scores[] = $reviews[$i]->getRating();
        }
        $scores = array_filter($scores);

        return (int) round(array_sum($scores) / count($scores));
    }
}

