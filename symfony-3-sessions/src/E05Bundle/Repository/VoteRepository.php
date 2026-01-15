<?php

namespace App\E05Bundle\Repository;

use App\E05Bundle\Entity\Vote;
use App\E03Bundle\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function countVotes(Post $post, int $value): int
    {
        return $this->count([
            'post' => $post,
            'value' => $value
        ]);
    }

    public function hasUserVoted(Post $post, User $user): bool
    {
        return (bool) $this->findOneBy([
            'post' => $post,
            'user' => $user
        ]);
    }

    public function getReputation(User $user): int
    {
        $qb = $this->createQueryBuilder('v')
            ->select('SUM(v.value)')
            ->join('v.post', 'p')
            ->where('p.author = :user')
            ->setParameter('user', $user);

        return (int) ($qb->getQuery()->getSingleScalarResult() ?? 0);
    }
}