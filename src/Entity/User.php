<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[UniqueEntity(fields: ["username"], message: 'There is already an account with this username.')]
#[UniqueEntity(fields: ["email"], message: 'There is already an account with this email.')]
#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    private string $username;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    private string $email;

    #[Assert\NotNull]
    #[ORM\Column(type: 'json')]
    private iterable $roles = [];

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $password;

    #[Assert\NotNull]
    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $isVerified = false;

    #[OnetoMany(
        mappedBy: 'owner',
        targetEntity: Review::class,
        cascade: ["persist", "remove"])
    ]
    private ?iterable $reviews = [];

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

    public function getReviews(): array|PersistentCollection|null
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setOwner($this);
        }
        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getOwner() === $this) {
                $review->setOwner(null);
            }
        }
        return $this;
    }


}
