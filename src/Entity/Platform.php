<?php

namespace App\Entity;

use App\Repository\PlatformRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatformRepository::class)]
class Platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $windows = null;

    #[ORM\Column]
    private ?bool $mac = null;

    #[ORM\Column]
    private ?bool $linux = null;

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
}
