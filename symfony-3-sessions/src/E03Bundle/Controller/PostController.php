<?php

namespace App\E03Bundle\Controller;

use App\E03Bundle\Entity\Post;
use App\E07Bundle\Service\ReputationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/e03')]
class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'e03_show')]
    #[IsGranted('ROLE_USER')]
    public function show(Post $post, ReputationService $reputationService): Response
    {
        return $this->render('e03/show.html.twig', [
            'post' => $post,
            'reputationService' => $reputationService,
        ]);
    }

    #[Route('/new', name: 'e03_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();

        if ($request->isMethod('POST')) {
            $post->setTitle($request->request->get('title'));
            $post->setContent($request->request->get('content'));
            $post->setAuthor($this->getUser());

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('e01_home');
        }

        return $this->render('e03/new.html.twig');
    }
}