<?php

namespace App\E05Bundle\Twig;

use App\E05Bundle\Repository\VoteRepository;
use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ReputationExtension extends AbstractExtension
{
    public function __construct(private VoteRepository $repo) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('reputation', [$this, 'getReputation']),
        ];
    }

    public function getReputation(User $user): int
    {
        return $this->repo->getReputation($user);
    }
}