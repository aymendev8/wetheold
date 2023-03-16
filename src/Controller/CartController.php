<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'cart')]
    public function index(): Response
    {
        return $this->render('pages/cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/ajouter_panier', name: 'cart.add')]
    public function cartAdd(): void
    {

    }
}