<?php

namespace App\Controller;

use App\Business\EntryHelper;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EntryController extends AbstractController
{
    private EntryHelper $helper;

    public function __construct(EntryHelper $helper)
    {
        $this->helper = $helper;
    }

    #[Route('/entry', name: 'entry')]
    public function index(): Response
    {
        return $this->render('entry/entry.html.twig',);
    }

    #[Route('/login', name: 'app_login', methods: 'POST')]
    public function login(IriConverterInterface $iriConverter): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }

        return new Response(null, 204, [
            'Location' => $iriConverter->getIriFromItem($this->getUser())
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('register', name: 'app_register', methods: "POST")]
    public function registerAction(Request $request): Response
    {
        $response = new Response();

        $user = $this->helper->makeUser($request);
        if ($user === null){
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }

        $this->helper->sendConfirmationEmail($user);
        $response->setStatusCode(Response::HTTP_ACCEPTED);
        return $response;
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->helper->verifyEmail($request);
        return $this->redirectToRoute('entry');
    }
}
