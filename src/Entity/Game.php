<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $appid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $detailed_description = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $about = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $short_description = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $supported_languages = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $header_image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255)]
    private ?string $developers = null;

    #[ORM\Column(length: 255)]
    private ?string $publishers = null;

    #[ORM\Column]
    private int $recommendations_total = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private bool $nsfw = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppid(): ?int
    {
        return $this->appid;
    }

    public function setAppid(int $appid): self
    {
        $this->appid = $appid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDetailedDescription(): ?string
    {
        return $this->detailed_description;
    }

    public function setDetailedDescription(?string $detailed_description): self
    {
        $this->detailed_description = $detailed_description;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    public function getSupportedLanguages(): ?string
    {
        return $this->supported_languages;
    }

    public function setSupportedLanguages(?string $supported_languages): self
    {
        $this->supported_languages = $supported_languages;

        return $this;
    }

    public function getHeaderImage(): ?string
    {
        return $this->header_image;
    }

    public function setHeaderImage(?string $header_image): self
    {
        $this->header_image = $header_image;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getDevelopers(): ?string
    {
        return $this->developers;
    }

    public function setDevelopers(string $developers): self
    {
        $this->developers = $developers;

        return $this;
    }

    public function getPublishers(): ?string
    {
        return $this->publishers;
    }

    public function setPublishers(string $publishers): self
    {
        $this->publishers = $publishers;

        return $this;
    }

    public function getRecommendationsTotal(): ?int
    {
        return $this->recommendations_total;
    }

    public function setRecommendationsTotal(int $recommendations_total): self
    {
        $this->recommendations_total = $recommendations_total;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function isNsfw(): ?bool
    {
        return $this->nsfw;
    }

    public function setNsfw(bool $nsfw): self
    {
        $this->nsfw = $nsfw;

        return $this;
    }
}
