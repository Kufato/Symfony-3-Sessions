<?php

namespace App\E07Bundle\Service;

use App\Entity\User;
use App\E03Bundle\Entity\Post;
use Symfony\Bundle\SecurityBundle\Security;

class ReputationService
{
    public function __construct(
        private Security $security
    ) {}

    public function isAdmin(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    public function canLike(User $user): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $user->getReputation() >= 3;
    }

    public function canDislike(User $user): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $user->getReputation() >= 6;
    }

    public function canEditPost(User $user, Post $post): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($user->getReputation() >= 9) {
            return true;
        }

        return $post->getAuthor() === $user;
    }
}