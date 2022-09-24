<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntryController extends AbstractController
{
    #[Route('/entry', name: 'entry')]
    public function index(): Response
    {
        return $this->render('entry/entry.html.twig',);
    }

    #[Route('/login', name: 'app_login', methods: 'POST')]
    public function login(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }

        return $this->json([
            'user' => $this->getUser() ? $this->getUser()->getUserIdentifier() : null,
        ]);
    }
}
