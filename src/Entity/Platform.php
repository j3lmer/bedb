<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PlatformRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    denormalizationContext: ['groups' => ['platform:write'], "swagger_definition_name" => "read"],
    normalizationContext: ['groups' => ['platform:read'], "swagger_definition_name" => "write"],
)]
#[ORM\Entity(repositoryClass: PlatformRepository::class)]
class Platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["platform:read"])]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Groups(["platform:read", "platform:write"])]
    #[ORM\Column(nullable: false)]
    private bool $windows;

    #[Assert\NotNull]
    #[Groups(["platform:read", "platform:write"])]
    #[ORM\Column(nullable: false)]
    private bool $mac;

    #[Assert\NotNull]
    #[Groups(["platform:read", "platform:write"])]
    #[ORM\Column(nullable: false)]
    private bool $linux;

    #[Assert\NotNull]
    #[Groups(["platform:read", "platform:write"])]
    #[ORM\OneToOne(inversedBy: 'platform', targetEntity: Game::class)]
    #[ORM\JoinColumn(name: 'game_id', nullable: false)]
    private Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isWindows(): ?bool
    {
        return $this->windows;
    }

    public function setWindows(bool $windows): self
    {
        $this->windows = $windows;

        return $this;
    }

    public function isMac(): ?bool
    {
        return $this->mac;
    }

    public function setMac(bool $mac): self
    {
        $this->mac = $mac;

        return $this;
    }

    public function isLinux(): ?bool
    {
        return $this->linux;
    }

    public function setLinux(bool $linux): self
    {
        $this->linux = $linux;

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
     * @return Platform
     */
    public function setGame(Game $game): self
    {
        $this->game = $game;
        return $this;
    }
}
