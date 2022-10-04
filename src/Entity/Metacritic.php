<?php

namespace App\Entity;

use App\Repository\MetacriticRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MetacriticRepository::class)]
class Metacritic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
//    #[Groups(["metacritic:read", "metacritic:write", "game:read"])]
    #[ORM\Column]
    private int $score;

//    #[Groups(["metacritic:read", "metacritic:write", "game:read"])]
    #[ORM\Column(length: 500)]
    private ?string $url = null;

    #[Assert\NotNull]
//    #[Groups(["metacritic:read", "metacritic:write"])]
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

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }
}
