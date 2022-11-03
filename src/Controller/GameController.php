<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GameController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/game/{steamAppId}', name: 'appDetails')]
    public function index(int $steamAppId): Response
    {
        return $this->render('game/game.html.twig',[
            'user' => $this->isGranted('IS_AUTHENTICATED_FULLY') ?
                $this->serializer->serialize($this->getUser(), 'jsonld') :
                null
        ]);
    }
}