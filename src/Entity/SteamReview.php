<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeamReviewRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 */
#[ORM\Entity(repositoryClass: TeamReviewRepository::class)]
class SteamReview
{
    #[Assert\NotNull]
    #[ORM\Id]
    #[ORM\Column(unique: true)]
    private int $id;

    #[Assert\NotNull]
    #[ORM\Column(nullable: false)]
    private float $hours;

    #[Assert\NotNull]
    #[ORM\Column(nullable: false)]
    private $recommended;

    #[ORM\Column(length: 8000, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $username;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?DateTimeInterface $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHours(): ?float
    {
        return $this->hours;
    }

    public function setHours(float $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function isRecommended(): ?bool
    {
        return $this->recommended;
    }

    public function setRecommended(bool $recommended): self
    {
        $this->recommended = $recommended;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
