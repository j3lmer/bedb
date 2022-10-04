<?php

namespace App\Entity;

use App\Repository\GenresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: GenresRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

//    #[Groups(["genre:read", "genre:write", "game:read"])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotNull]
//    #[Groups(["category:read", "category:write"])]
    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'genres' )]
    #[ORM\JoinColumn(name: 'game_id', nullable: false)]
    private ?iterable $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
    *   @return iterable|ArrayCollection
    */
    public function getGames(): ArrayCollection|iterable
    {
        return $this->games;
    }
    public function setGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addGenre($this);
        }
        return $this;
    }
    public function removeGame(Game $question): self
    {
        if ($this->games->removeElement($question)) {
            $question->removeGenre($this);
        }
        return $this;
    }
}
