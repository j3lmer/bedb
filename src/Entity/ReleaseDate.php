<?php

namespace App\Entity;

use App\Repository\ReleaseDateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReleaseDateRepository::class)]
class ReleaseDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $coming_soon = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date = null;

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
