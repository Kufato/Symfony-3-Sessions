<?php

namespace App\E03Bundle\Entity;

use App\E03Bundle\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\E05Bundle\Entity\Vote;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private User $author;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Vote::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $votes;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $updatedBy = null;

    // Constructor
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->votes = new ArrayCollection();
    }

    // Getters & setters
    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): self
    {
        $this->title = $title; return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content; return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author; return $this;
    }

    public function getVotes(): Collection
    {
        return $this->votes; }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(User $user): self
    {
        $this->updatedBy = $user;
        return $this;
    }
}