<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={
 *          "groups"={
 *              "game:read",
 *              "game:item:get"
 *          }
 *     },
 *
 *     denormalizationContext={
 *          "groups"={"game:write"}
 *     },
 *
 *     collectionOperations={
 *          "get",
 *          "post"={"security"="is_granted('ROLE_ADMIN')"}
 *      },
 *
 *     itemOperations={
 *          "get",
 *          "put"={"security"="is_granted('ROLE_ADMIN')"}
 *      },
 *     shortName="Games"
 * )
 */
#[UniqueEntity(fields: ["appid"], message: 'Another game already has this appid')]
#[ORM\Table(name: '`game`')]
#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\Column(unique: true, nullable: false)]
    private int $id; // steam appid

    #[Assert\NotNull]
    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

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

    #[Assert\NotNull]
    #[ORM\Column(nullable: false)]
    private bool $nsfw = true;

    #[ORM\OneToOne(mappedBy: 'game', targetEntity: PcRequirement::class)]
    private ?PcRequirement $pc_requirement = null;

    #[ORM\OneToOne(mappedBy: 'game', targetEntity: Platform::class)]
    private Platform $platform;

    #[ORM\OneToOne(mappedBy: 'game', targetEntity: Metacritic::class)]
    private Metacritic $metacritic;

    #[ORM\OneToOne(mappedBy: 'game', targetEntity: ReleaseDate::class)]
    private ReleaseDate $release_date;

    #[Groups("game:read")]
    #[OnetoMany(
        mappedBy: 'owner',
        targetEntity: Review::class,
        cascade: ["persist", "remove"])
    ]
    private iterable $reviews = [];

    #[OnetoMany(
        mappedBy: 'game',
        targetEntity: Category::class,
        cascade: ["persist", "remove"])
    ]
    private iterable $categories;

    #[OnetoMany(
        mappedBy: 'game',
        targetEntity: Genre::class,
        cascade: ["persist", "remove"])
    ]
    private iterable $genres;

    #[OnetoMany(
        mappedBy: 'game',
        targetEntity: Screenshot::class,
        cascade: ["persist", "remove"])
    ]
    private iterable $screenshots;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getReviews(): ?array
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setGame($this);
        }
        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getGame() === $this) {
                $review->setGame(null);
            }
        }
        return $this;
    }
}
