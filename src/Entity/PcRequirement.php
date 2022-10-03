<?php

namespace App\Entity;

use App\Repository\PcRequirementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PcRequirementRepository::class)]
class PcRequirement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000, nullable: false)]
    private string $minimum;

    #[ORM\Column(length: 1000, nullable: false)]
    private string $recommended;

    #[Assert\NotNull]
    #[ORM\OneToOne(inversedBy: 'pc_requirement', targetEntity: Game::class)]
    private Game $game;

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

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }
}
