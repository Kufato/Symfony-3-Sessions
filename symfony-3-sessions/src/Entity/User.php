<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\E03Bundle\Entity\Post;
use App\E05Bundle\Entity\Vote;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Properties //
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Password cannot be empty.")]
    #[Assert\Regex(
        pattern: "/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/",
        message: "Password must be at least 8 characters, include one uppercase letter and one special character."
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vote::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $votes;

    // Getter & setter //
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }
    
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getReputation(): int
    {
        $score = 0;

        foreach ($this->getPosts() as $post) {
            foreach ($post->getVotes() as $vote) {
                $score += $vote->isLike() ? 1 : -1;
            }
        }

        return $score;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }
    
    // Symfony Security //
    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}