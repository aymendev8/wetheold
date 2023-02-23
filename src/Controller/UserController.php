<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profil/{id}', name: 'user.profil')]
    public function index(User $user): Response
    {
        if($user->getId() !== $this->getUser()->getId()){
            return $this->redirectToRoute('homepage');
        }

        return $this->render('pages/user/profil.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
