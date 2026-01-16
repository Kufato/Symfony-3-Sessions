<?php

namespace App\E06Bundle\Controller;

use App\E03Bundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/e06')]
#[IsGranted('ROLE_USER')]
class PostEditController extends AbstractController
{
    #[Route('/edit/{id}', name: 'e06_edit')]
    public function edit(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $post->setTitle($request->request->get('title'));
            $post->setContent($request->request->get('content'));
            $post->setUpdatedAt(new \DateTimeImmutable());
            $post->setUpdatedBy($this->getUser());

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('e03_show', ['id' => $post->getId()]);
        }

        return $this->render('e06/edit.html.twig', [
            'post' => $post
        ]);
    }
}