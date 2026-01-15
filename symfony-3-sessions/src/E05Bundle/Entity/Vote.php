<?php

namespace App\E05Bundle\Entity;

use App\Entity\User;
use App\E03Bundle\Entity\Post;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(
    uniqueConstraints: [
        new ORM\UniqueConstraint(columns: ['user_id', 'post_id'])
    ]
)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Post $post;

    #[ORM\Column(type: 'smallint')]
    private int $value; // +1 = like, -1 = dislike

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;
        return $this;
    }
}