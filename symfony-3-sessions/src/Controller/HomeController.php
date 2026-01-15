<?php

namespace App\Controller;

use App\E03Bundle\Repository\PostRepository;
use App\E04Bundle\Service\AnonymousSessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/e01')]
final class HomeController extends AbstractController
{
    #[Route('/home', name: 'e01_home')]
    public function index(
        PostRepository $postRepository,
        AnonymousSessionService $anonymousSessionService
    ): Response {
        $anonymousData = null;

        if (!$this->getUser()) {
            $anonymousData = $anonymousSessionService->handle();
        }

        return $this->render('home/index.html.twig', [
            'posts' => $postRepository->findLatest(),
            'anonymous' => $anonymousData,
        ]);
    }
}