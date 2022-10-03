<?php

namespace App\Entity;

use App\Repository\PlatformRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlatformRepository::class)]
class Platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[ORM\Column(nullable: false)]
    private bool $windows;

    #[Assert\NotNull]
    #[ORM\Column(nullable: false)]
    private bool $mac;

    #[Assert\NotNull]
    #[ORM\Column(nullable: false)]
    private bool $linux;

    #[Assert\NotNull]
    #[ORM\OneToOne(inversedBy: 'pc_requirement', targetEntity: Game::class)]
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
