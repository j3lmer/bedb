<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Review;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Service\FileUploader;

#[AsController]
class ReviewFileController extends AbstractController
{
    //todo: ook even data valideren
    public function __invoke(Request $request, FileUploader $fileUploader, GameRepository $gameRepository, UserRepository $userRepository, string $publicPath): Review
    {
        $review = new Review();

        $owner = $this->getUser() !== null ? $this->getUser() : $userRepository->find($this->getSinceLastSlash($request->get('owner')));

        //request komt niet binnen via axios(?)
        $game = $gameRepository->find($this->getSinceLastSlash($request->get('game')));
        $review->setGame($game);
        $review->setOwner($owner);
        $review->setRating((int)$request->get('rating'));
        $review->setText($request->get('text'));

        $uploadedFile = $request->files->get('image');
        if ($uploadedFile) {
            $review->setImageName($fileUploader->upload($uploadedFile));
        }
        $review->setDateUpdated(new \DateTime('now'));

        return $review;
    }


    private function getSinceLastSlash(string $x): string
    {
        return substr($x, strrpos($x, '/') + 1);
    }
}