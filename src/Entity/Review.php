<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\ReviewRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use App\Controller\ReviewFileController;

///**
// * @ApiResource(
// *     normalizationContext={
// *          "groups"={
// *              "review:read",
// *              "review:item:get",
// *          }
// *     },
// *
// *     denormalizationContext={
// *          "groups"={"review:write"}
// *     },
// *
// *     collectionOperations={
// *          "get",
// *          "post"={"security"="is_granted('ROLE_USER')"}
// *      },
// *
// *     itemOperations={
// *          "get"={"normalization_context"={"groups"={"review:read", "review:item:get"}},},
// *          "put"={"security"="is_granted('ROLE_USER')"},
// *          "delete"={"security"="is_granted('ROLE_USER')"}
// *      },
// *     shortName="Review"
// * )
// */

/**
 * @ApiResource(
 *   collectionOperations={
 *     "get",
 *     "post" = {
 *       "controller" = ReviewFileController::class,
 *       "deserialize" = false,
 *       "openapi_context" = {
 *          "requestBody" = {
 *            "description" = "File upload to an existing resource (Review)",
 *            "required" = false,
 *            "content" = {
 *              "multipart/form-data" = {
 *                "schema" = {
 *                  "type" = "object",
 *                   "properties" = {
 *                      "text" = {
 *                        "description" = "The review text the user has submitted for a game",
 *                        "type" = "string",
 *                        "example" = "Dit vind ik geen leuk spelletje"
 *                      },
 *                      "rating" = {
 *                          "description" = "The numerical rating a user has left on a game",
 *                          "type" = "integer",
 *                          "example" = "5"
 *                      },
 *                      "image" = {
 *                          "description" = "An image file a user can upload supporting their review",
 *                          "type" = "string",
 *                          "format" = "binary",
 *                      },
 *                      "game" = {
 *                          "description" = "The associated game with the review",
 *                          "type" = "string",
 *                          "example" = "/api/games/1"
 *                      },
 *                      "owner" = {
 *                          "description" = "The id of the user associated with this review",
 *                          "type" = "string",
 *                          "example" = "/api/users/1"
 *                      }
 *                  },
 *                },
 *              },
 *            },
 *          },
 *       },
 *     },
 *   },
 *)
 */
#[ApiFilter(OrderFilter::class, properties: ['id' => 'DESC', 'reported' => 'exact'])]
#[Uploadable]
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8000, nullable: true)]
    private ?string $text = null;

    #[Assert\NotNull]
    #[Assert\Range(notInRangeMessage: "Rating must be between 1 and 10", min: 1, max: 10)]
    #[ORM\Column(nullable: false)]
    private int $rating;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private DateTimeInterface $date_updated;

    /**
     * @ApiProperty(
     *   iri="http://schema.org/image",
     *   attributes={
     *     "openapi_context"={
     *       "type"="string",
     *     }
     *   }
     * )
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $imageName;

    #[ORM\Column]
    private bool $reported = false;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(name: 'owner_id', nullable: false)]
    private ?User $owner;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(name: 'game_id', nullable: false)]
    private ?Game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getDateUpdated(): ?DateTimeInterface
    {
        return $this->date_updated;
    }

    public function setDateUpdated(DateTimeInterface $date_updated): self
    {
        $this->date_updated = $date_updated;

        return $this;
    }

    public function setImage(File $file = null): self
    {
        $this->image = $file;
        if ($file) {
            $this->date_updated = new \DateTime('now');
        }

        return $this;
    }

    public function getImage(): File
    {
        return $this->image;
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReported(): bool
    {
        return $this->reported;
    }

    /**
     * @param bool $reported
     */
    public function setReported(bool $reported): void
    {
        $this->reported = $reported;
    }
}
