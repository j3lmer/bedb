<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReleaseDateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    denormalizationContext: ['groups' => ['release_date:write'], "swagger_definition_name" => "read"],
    normalizationContext: ['groups' => ['release_date:read'], "swagger_definition_name" => "write"],
)]
#[ORM\Entity(repositoryClass: ReleaseDateRepository::class)]
class ReleaseDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["release_date:read"])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["release_date:read", "release_date:write"])]
    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private bool $comingSoon;

    #[Groups(["release_date:read", "release_date:write"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date = null;

    #[Assert\NotNull]
    #[Groups(["release_date:read", "release_date:write"])]
    #[ORM\OneToOne(inversedBy: 'release_date', targetEntity: Game::class)]
    #[ORM\JoinColumn(name: 'game_id', nullable: false)]
    private Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isComingSoon(): bool
    {
        return $this->comingSoon;
    }

    /**
     * @param bool $comingSoon
     */
    public function setComingSoon(bool $comingSoon): self
    {
        $this->comingSoon = $comingSoon;
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

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     * @return ReleaseDate
     */
    public function setGame(Game $game): self
    {
        $this->game = $game;
        return $this;
    }
}
