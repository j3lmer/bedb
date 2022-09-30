<?php
declare(strict_types=1);

namespace App\Business;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Business\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class EntryHelper
{

    private EmailVerifier $emailVerifier;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;

    public function __construct(
        EmailVerifier               $emailVerifier,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository              $userRepository
    )
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function setInformation(mixed $requestData, User $registree): bool
    {
        try {
            $registree->setUsername($requestData['username']);
            $registree->setEmail($requestData['email']);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function makeUser(Request $request): ?User
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        if (!$this->setInformation($data, $user))
            return null;

        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $data['plainPassword']
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmail(User $user): void
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('_mainaccount@j3lmer.fc.school', 'Bedb'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('entry/confirmation_email.html.twig')
        );
    }

    public function verifyEmail(Request $request): bool
    {
        $id = $request->get('id');
        if (null === $id)
            return false;

        $user = $this->userRepository->find($id);
        if (null === $user)
            return false;

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            return false;
        }

        return true;
    }

}