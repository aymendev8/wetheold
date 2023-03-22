<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Wishlist;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security.login',methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/index.html.twig', [
            'last_username' =>$authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'security.logout', methods: ['GET', 'POST'])]
    public function logout(): void
    {
    }

    #[Route('/register', name: 'security.register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $manager->flush();


            $cart = new Cart();
            $cart->setIdUser($user);
            $manager->persist($cart);
            $manager->flush();

            $wishlist = new Wishlist();
            $wishlist->setUser($user);
            $wishlist->setNbArticles(0);
            $manager->persist($wishlist);
            $manager->flush();

            $this->addFlash('success', 'Votre compte a bien été créé');
            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
