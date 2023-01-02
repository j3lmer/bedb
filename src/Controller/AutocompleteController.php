<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutocompleteController extends AbstractController
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    #[Route('/getGame', name: 'app_game_autocomplete', methods: "POST")]
    public function getGame(Request $request): Response
    {
        $name = $request->get('gameName');

         $result = $this->gameRepository->createQueryBuilder('g')
             ->where("g.name LIKE '%$name%'")
             ->setMaxResults(10)
             ->getQuery()
             ->getArrayResult();
         return $result !== null ? new JsonResponse($result) : new Response("nothing found", 404);
    }
}