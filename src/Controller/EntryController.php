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
    public function login(#[CurrentUser] ?User $user, IriConverterInterface $iriConverter): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
         }

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
        $user = $this->helper->makeUser($request);
        if ($user === null){
            //TODO: RETURN CORRECT RESPONSE
            dd($user);
        }

        $this->helper->sendConfirmationEmail($user);
        //TODO: RETURN CORRECT RESPONSE
        return $this->redirectToRoute('home');
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $processed = $this->helper->verifyEmail($request);
        if (!$processed)
            return $this->redirectToRoute('entry');

        //TODO: return to main page or page user came from initially
        return $this->redirectToRoute('home');
    }
}
