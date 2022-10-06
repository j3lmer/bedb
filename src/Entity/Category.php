<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    denormalizationContext: ['groups' => ['category:write'], "swagger_definition_name" => "read"],
    normalizationContext: ['groups' => ['category:read'], "swagger_definition_name" => "write"],
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["category:read", "category:write"])]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["category:read", "category:write"])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotNull]
    #[Groups(["category:read", "category:write"])]
    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'categories' )]
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

    public function setGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addCategory($this);
        }
        return $this;
    }

    public function removeGame(Game $question): self
    {
        if ($this->games->removeElement($question)) {
            $question->removeCategory($this);
        }
        return $this;
    }
}
