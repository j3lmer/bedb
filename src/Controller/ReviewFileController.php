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

    //TODO: dit is vooral voor een nieuwe aan te maken, mijn gedachte was meer een bestaande updaten
    //todo: ook even data valideren
    public function __invoke(Request $request, FileUploader $fileUploader, GameRepository $gameRepository, UserRepository $userRepository, string $publicPath): Review
    {
        $uploadedFile = $request->files->get('image');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $review = new Review();

        $owner = $this->getUser() !== null ? $this->getUser() : $userRepository->find($this->getSinceLastSlash($request->get('owner')));

        $game = $gameRepository->find($this->getSinceLastSlash($request->get('game')));
        $review->setGame($game);
        $review->setOwner($owner);
        $review->setRating((int)$request->get('rating'));
        $review->setText($request->get('text'));
        $review->setImageName($fileUploader->upload($uploadedFile));
        $review->setImage(new File($publicPath . '/uploads/' . $review->getImageName()));

        $review->setDateUpdated(new \DateTime('now'));

        return $review;
    }


    private function getSinceLastSlash(string $x): string
    {
        return substr($x, strrpos($x, '/') + 1);
    }
}