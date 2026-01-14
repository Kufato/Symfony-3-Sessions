<?php

namespace App\E02Bundle\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/e02')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'e02_admin')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('e02/admin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'e02_admin_delete', methods: ['POST'])]
    public function delete(
        User $user,
        EntityManagerInterface $em
    ): Response {
        if ($user === $this->getUser()) {
            $this->addFlash('error', 'You cannot delete your own account.');
            return $this->redirectToRoute('e02_admin');
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('e02_admin');
    }
}