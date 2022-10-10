<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GenresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    denormalizationContext: ['groups' => ['genre:write'], "swagger_definition_name" => "read"],
    normalizationContext: ['groups' => ['genre:read'], "swagger_definition_name" => "write"],
)]
#[ORM\Entity(repositoryClass: GenresRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["genre:read"])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["genre:read", "genre:write"])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotNull]
    #[Groups(["genre:read", "genre:write"])]
    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'genres')]
    #[ORM\JoinColumn(name: 'game_id', nullable: false)]
    private iterable $games;

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
     * @return ArrayCollection|iterable
     */
    public function getGames(): ArrayCollection|iterable
    {
        return $this->games;
    }

    public function getGame(int $id): Game|null
    {
        foreach($this->games as $game) {
            if ($game->getId() === $id) {
                return $game;
            }
        }
        return null;
    }

    public function addGame($game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addGenre($this);
        }
        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removeGenre($this);
        }
        return $this;
    }
}
