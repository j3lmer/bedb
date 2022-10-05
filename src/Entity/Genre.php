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
    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'genres')]
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
     * @return iterable|null
     */
    public function getGames(): ?iterable
    {
        return $this->games;
    }

    public function addGame(Game $game):void
    {
        if (!$this->games->contains($game)) {
            $game->addGenre($this);
            $this->games->add($game);
        }
    }

    public function removeGame(Game $game):void
    {
        if ($this->games->contains($game)) {
            $game->addGenre($this);
            $this->games->removeElement($game);
        }
    }
}
