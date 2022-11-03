<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/settings', name: 'userSettings')]
    public function index(): Response
    {
        $user = $this->isGranted('IS_AUTHENTICATED_FULLY') ?
            $this->serializer->serialize($this->getUser(), 'jsonld') :
            null;

        if ($user === null) {
            return $this->redirectToRoute('home');
        }

        return $this->render('user/user.html.twig', ['user' => $user]);
    }
}