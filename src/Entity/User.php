<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 * )
 */
#[UniqueEntity(fields: ["username"], message: 'There is already an account with this username.')]
#[UniqueEntity(fields: ["email"], message: 'There is already an account with this email.')]
#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[NotNull]
    #[NotBlank]
    #[Groups(["user:read", "user:write"])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $username;

    #[NotNull]
    #[NotBlank]
    #[Email]
    #[Groups(["user:read", "user:write"])]
    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    private $email;

    #[NotNull]
    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[NotNull]
    #[Groups("user:write")]
    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[NotNull]
    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

//    #[ORM\Column(type: 'Reviews[]', nullable: true)]
//    #[OnetoMany(
//        mappedBy: 'owner',
//        targetEntity: Review::class,
//        cascade: ["persist", "remove"])
//    ]
//    private iterable $reviews = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getReviews(): ?array
    {
        return $this->reviews;
    }

    public function setReviews(?array $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

//    public function removeReservation(Review $reservation): self
//    {
//        if ($this->reviews->contains($reservation)) {
//            $this->reviews->removeElement($reservation);
//            // set the owning side to null (unless already changed)
//            if ($reservation->getOwner() === $this) {
//                $reservation->setOwner(null);
//            }
//        }
//        return $this;
//    }

//    /**
//     * @return iterable|ArrayCollection
//     */
//    public function getReservations(): iterable|ArrayCollection
//    {
//        return $this->reviews;
//    }
}
