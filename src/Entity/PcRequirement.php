<?php

namespace App\Entity;

use App\Repository\PcRequirementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PcRequirementRepository::class)]
class PcRequirement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $minimum = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $recommended = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinimum(): ?string
    {
        return $this->minimum;
    }

    public function setMinimum(?string $minimum): self
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function getRecommended(): ?string
    {
        return $this->recommended;
    }

    public function setRecommended(?string $recommended): self
    {
        $this->recommended = $recommended;

        return $this;
    }
}
