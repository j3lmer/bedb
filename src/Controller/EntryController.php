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
}
