<?php

namespace App\E05Bundle\Entity;

use App\Entity\User;
use App\E03Bundle\Entity\Post;
use App\E05Bundle\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ORM\Table(name: 'vote')]
#[ORM\UniqueConstraint(columns: ['user_id', 'post_id'])]
class Vote
{
    // Properties
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private Post $post;

    #[ORM\Column]
    private int $value; // 1 = like, -1 = dislike

    // Getter & setter
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;
        return $this;
    }

    // Other methods
    public function isLike(): bool
    {
        return $this->value === 1;
    }

    public function isDislike(): bool
    {
        return $this->value === -1;
    }
}