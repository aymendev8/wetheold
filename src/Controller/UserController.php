<?php

namespace App\Controller;

use App\Form\EditUserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class UserController extends AbstractController
{
    #[Route('/profil', name: 'user.profil',methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        $form = $this->createForm(UserType::class, $this->getUser());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour');
            return $this->redirectToRoute('user.profil');
        }
        return $this->render('pages/user/profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/profil/commande', name: 'user.commande')]
    public function commande(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('security.login');
        }
        return $this->render('pages/user/commande.html.twig');
    }
    #[Route('/profil/password', name: 'user.password',methods: ['GET', 'POST'])]
    public function modifmdp(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('security.login');
        }
        $form = $this->createForm(EditUserPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (password_verify($form->get('plainPassword')->getData(), $user->getPassword())) {
                $user->setPassword(password_hash($form->get('newPassword')->getData(), PASSWORD_BCRYPT));
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Votre mot de passe a bien été mis à jour');
                return $this->redirectToRoute('user.profil');
            } else {
                $this->addFlash('danger', 'Votre mot de passe actuel est incorrect');
            }
        }
        return $this->render('pages/user/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profil/delete', name: 'user.delete')]
    public function delete(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('security.login');
        }
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Votre compte a bien été supprimé');
        return $this->redirectToRoute('homepage');
    }
}

