<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PcRequirementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    denormalizationContext: ['groups' => ['pc_requirement:write'], "swagger_definition_name" => "read"],
    normalizationContext: ['groups' => ['pc_requirement:read'], "swagger_definition_name" => "write"],
)]
#[ORM\Entity(repositoryClass: PcRequirementRepository::class)]
class PcRequirement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["release_date:read"])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["release_date:read", "release_date:write"])]
    #[ORM\Column(length: 1000, nullable: false)]
    private string $minimum;

    #[Groups(["release_date:read", "release_date:write"])]
    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $recommended = null;

    #[Assert\NotNull]
    #[Groups(["release_date:read", "release_date:write"])]
    #[ORM\OneToOne(inversedBy: 'pc_requirement', targetEntity: Game::class)]
    #[ORM\JoinColumn(name: 'game_id', nullable: false)]
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
     * @return PcRequirement
     */
    public function setGame(Game $game): self
    {
        $this->game = $game;
        return $this;
    }
}
