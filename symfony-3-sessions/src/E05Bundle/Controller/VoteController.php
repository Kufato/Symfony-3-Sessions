<?php

namespace App\E05Bundle\Controller;

use App\E05Bundle\Entity\Vote;
use App\E05Bundle\Repository\VoteRepository;
use App\E03Bundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/e05')]
#[IsGranted('ROLE_USER')]
class VoteController extends AbstractController
{
    #[Route('/vote/{id}/{value}', name: 'e05_vote')]
    public function vote(
        Post $post,
        int $value,
        VoteRepository $voteRepo,
        EntityManagerInterface $em
    ) {
        $user = $this->getUser();

        if ($voteRepo->hasUserVoted($post, $user)) {
            return $this->redirectToRoute('e03_show', ['id' => $post->getId()]);
        }

        $vote = new Vote();
        $vote->setUser($user);
        $vote->setPost($post);
        $vote->setValue($value);

        $em->persist($vote);
        $em->flush();

        return $this->redirectToRoute('e03_show', ['id' => $post->getId()]);
    }
}