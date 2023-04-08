<?php

namespace App\Controller;

use App\Entity\CarteBancaire;
use App\Form\CarteBancaireType;
use App\Repository\CarteBancaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carte/bancaire')]

class CarteBancaireController extends AbstractController
{
    #[Route('/', name: 'app_carte_bancaire_index', methods: ['GET'])]
    public function index(CarteBancaireRepository $carteBancaireRepository): Response
    {
        @$this->isGranted('ROLE_ADMIN');
        return $this->render('carte_bancaire/index.html.twig', [
            'carte_bancaires' => $carteBancaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_carte_bancaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarteBancaireRepository $carteBancaireRepository): Response
    {
        @$this->isGranted('ROLE_ADMIN');
        $carteBancaire = new CarteBancaire();
        $form = $this->createForm(CarteBancaireType::class, $carteBancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carteBancaireRepository->save($carteBancaire, true);

            return $this->redirectToRoute('app_carte_bancaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carte_bancaire/new.html.twig', [
            'carte_bancaire' => $carteBancaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carte_bancaire_show', methods: ['GET'])]
    public function show(CarteBancaire $carteBancaire): Response
    {
        @$this->isGranted('ROLE_ADMIN');
        return $this->render('carte_bancaire/show.html.twig', [
            'carte_bancaire' => $carteBancaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carte_bancaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CarteBancaire $carteBancaire, CarteBancaireRepository $carteBancaireRepository): Response
    {
        if (count($this->getUser()->getRoles()) == 1) {
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(CarteBancaireType::class, $carteBancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carteBancaireRepository->save($carteBancaire, true);

            return $this->redirectToRoute('app_carte_bancaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carte_bancaire/edit.html.twig', [
            'carte_bancaire' => $carteBancaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carte_bancaire_delete', methods: ['POST'])]
    public function delete(Request $request, CarteBancaire $carteBancaire, CarteBancaireRepository $carteBancaireRepository): Response
    {
        if (count($this->getUser()->getRoles()) == 1) {
            return $this->redirectToRoute('homepage');
        }
        if ($this->isCsrfTokenValid('delete'.$carteBancaire->getId(), $request->request->get('_token'))) {
            $carteBancaireRepository->remove($carteBancaire, true);
        }

        return $this->redirectToRoute('app_carte_bancaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
