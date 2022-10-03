<?php

namespace App\Entity;

use App\Repository\ReleaseDateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReleaseDateRepository::class)]
class ReleaseDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["release_date:read", "release_date:write", "game:read"])]
    #[ORM\Column(length: 500)]
    private ?bool $coming_soon = null;

    #[Groups(["release_date:read", "release_date:write", "game:read"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date = null;

    #[Assert\NotNull]
    #[Groups(["release_date:read", "release_date:write"])]
    #[ORM\OneToOne(inversedBy: 'release_date', targetEntity: Game::class)]
    private Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isComingSoon(): ?bool
    {
        return $this->coming_soon;
    }

    public function setComingSoon(bool $coming_soon): self
    {
        $this->coming_soon = $coming_soon;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }
}
