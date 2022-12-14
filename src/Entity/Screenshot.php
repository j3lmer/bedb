<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ScreenshotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    denormalizationContext: ['groups' => ['screenshot:write'], "swagger_definition_name" => "read"],
    normalizationContext: ['groups' => ['screenshot:read'], "swagger_definition_name" => "write"],
)]
#[ORM\Entity(repositoryClass: ScreenshotRepository::class)]
class Screenshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups("screenshot:read")]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["screenshot:read", "screenshot:write"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[Groups(["screenshot:read", "screenshot:write"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $full = null;

    #[Assert\NotNull]
    #[Groups(["screenshot:read", "screenshot:write"])]
    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'screenshots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getFull(): ?string
    {
        return $this->full;
    }

    public function setFull(string $full): self
    {
        $this->full = $full;

        return $this;
    }

    /**
     * @return Game|null
     */
    public function getGame(): ?Game
    {
        return $this->game;
    }

    /**
     * @param Game|null $game
     */
    public function setGame(?Game $game): void
    {
        $this->game = $game;
    }
}
