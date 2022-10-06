<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[UniqueEntity(fields: ["id"], message: 'Another game already has this appid')]
#[ORM\Table(name: '`game`')]
#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\Column(unique: true, nullable: false)]
    private int $id; // steam appid

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $developers = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $publishers = [];

    #[Assert\NotNull]
    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(length: 8000, nullable: true)]
    private ?string $detailed_description = null;

    #[ORM\Column(length: 8000, nullable: true)]
    private ?string $about = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $short_description = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $supported_languages = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $header_image;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column]
    private int $recommendations_total = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[Assert\NotNull]
    #[ORM\Column(nullable: false)]
    private bool $nsfw = true;

    #[ORM\OneToOne(
        mappedBy: 'game',
        targetEntity: PcRequirement::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private ?PcRequirement $pc_requirement = null;

    #[ORM\OneToOne(
        mappedBy: 'game',
        targetEntity: Platform::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private Platform $platform;

    #[ORM\OneToOne(
        mappedBy: 'game',
        targetEntity: Metacritic::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private Metacritic $metacritic;

    #[ORM\OneToOne(
        mappedBy: 'game',
        targetEntity: ReleaseDate::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private ReleaseDate $release_date;

    #[OnetoMany(
        mappedBy: 'game',
        targetEntity: Review::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private ?iterable $reviews = [];

    #[OnetoMany(
        mappedBy: 'game',
        targetEntity: SteamReview::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private ?iterable $steam_reviews = [];

    #[OnetoMany(
        mappedBy: 'game',
        targetEntity: Screenshot::class,
        cascade: ["persist", "remove"],
//        orphanRemoval: true
    )]
    private ?iterable $screenshots;

    #[ORM\ManyToMany(
        targetEntity: Category::class,
        inversedBy: 'games',
        cascade: ["persist", "remove"],
//        orphanRemoval: true
    )]
    private iterable $categories;

    #[ORM\ManyToMany(
        targetEntity: Genre::class,
        inversedBy: 'games',
        cascade: ["persist", "remove"],
//        orphanRemoval: true
    )]
    private ?iterable $genres;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->screenshots = new ArrayCollection();
        $this->steam_reviews = new ArrayCollection();
    }

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

    /**
     * @return PcRequirement|null
     */
    public function getPcRequirement(): ?PcRequirement
    {
        return $this->pc_requirement;
    }

    /**
     * @param PcRequirement|null $pc_requirement
     */
    public function setPcRequirement(?PcRequirement $pc_requirement): void
    {
        $this->pc_requirement = $pc_requirement;
    }

    /**
     * @return Platform
     */
    public function getPlatform(): Platform
    {
        return $this->platform;
    }

    /**
     * @param Platform $platform
     */
    public function setPlatform(Platform $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @return Metacritic
     */
    public function getMetacritic(): Metacritic
    {
        return $this->metacritic;
    }

    /**
     * @param Metacritic $metacritic
     */
    public function setMetacritic(Metacritic $metacritic): void
    {
        $this->metacritic = $metacritic;
    }


    /**
     * @return ReleaseDate
     */
    public function getReleaseDate(): ReleaseDate
    {
        return $this->release_date;
    }

    /**
     * @param ReleaseDate $release_date
     */
    public function setReleaseDate(ReleaseDate $release_date): void
    {
        $this->release_date = $release_date;
    }


    public function getReviews(): ArrayCollection|iterable
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


    public function getSteamReviews(): ArrayCollection|iterable
    {
        return $this->steam_reviews;
    }

    public function addSteamReview(SteamReview $review): self
    {
        if (!$this->steam_reviews->contains($review)) {
            $this->steam_reviews[] = $review;
            $review->setGame($this);
        }
        return $this;
    }

    public function removeSteamReview(SteamReview $review): self
    {
        if ($this->steam_reviews->contains($review)) {
            $this->steam_reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getGame() === $this) {
                $review->setGame(null);
            }
        }
        return $this;
    }


    public function getScreenshots(): iterable
    {
        return $this->screenshots;
    }

    public function addScreenshot(Screenshot $screenshot): self
    {
        if (!$this->screenshots->contains($screenshot)) {
            $this->screenshots[] = $screenshot;
            $screenshot->setGame($this);
        }
        return $this;
    }

    public function removeScreenshot(Screenshot $screenshot): self
    {
        if ($this->screenshots->contains($screenshot)) {
            $this->screenshots->removeElement($screenshot);
            // set the owning side to null (unless already changed)
            if ($screenshot->getGame() === $this) {
                $screenshot->setGame(null);
            }
        }
        return $this;
    }

    /**
     * @return iterable|ArrayCollection
     */
    public function getCategories(): ArrayCollection|iterable
    {
        return $this->categories;
    }
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setGame($this);
        }
        return $this;
    }
    public function removeCategory(Category $category): self
    {
        if ($this->genres->contains($category)) {
            $this->genres->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getGames()->contains($this)) {
                $category->removeGame($this);
            }
        }
        return $this;
    }

    public function getGenres(): iterable
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->setGame($this);
        }
        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
            // set the owning side to null (unless already changed)
            if ($genre->getGames()->contains($this)) {
                $genre->removeGame($this);
            }
        }
        return $this;
    }
}
