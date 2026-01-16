<?php

namespace App\E05Bundle\Controller;

use App\E05Bundle\Entity\Vote;
use App\E05Bundle\Repository\VoteRepository;
use App\E03Bundle\Entity\Post;
use App\E07Bundle\Service\ReputationService;
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
        EntityManagerInterface $em,
        ReputationService $reputationService
    ) {
        $user = $this->getUser();

        if ($value === 1 && !$reputationService->canLike($user)) {
            $this->addFlash('error', 'You need at least 3 reputation points to like posts.');
            return $this->redirectToRoute('e03_show', ['id' => $post->getId()]);
        }

        if ($value === -1 && !$reputationService->canDislike($user)) {
            $this->addFlash('error', 'You need at least 6 reputation points to dislike posts.');
            return $this->redirectToRoute('e03_show', ['id' => $post->getId()]);
        }

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