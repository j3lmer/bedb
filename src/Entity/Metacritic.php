<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MetacriticRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    denormalizationContext: ['groups' => ['metacritic:write'], "swagger_definition_name" => "read"],
    normalizationContext: ['groups' => ['metacritic:read'], "swagger_definition_name" => "write"],
)]
#[ORM\Entity(repositoryClass: MetacriticRepository::class)]
class Metacritic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups("metacritic:read")]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Groups(["metacritic:read", "metacritic:write"])]
    #[ORM\Column]
    private int $score;

    #[Assert\NotNull]
    #[Groups(["metacritic:read", "metacritic:write"])]
    #[ORM\Column(length: 500)]
    private string $url;

    #[Assert\NotNull]
    #[Groups(["metacritic:read", "metacritic:write"])]
    #[ORM\OneToOne(inversedBy: 'metacritic', targetEntity: Game::class)]
    #[ORM\JoinColumn(name: 'game_id', nullable: false)]
    private Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
