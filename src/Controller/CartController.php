<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProductCartRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'cart')]
    public function index( UserRepository $userRepository, ProductCartRepository $productCartRepository): Response
    {
        $user = $userRepository->find($this->getUser()->getId());
        $cart = $user->getCart()->getId();
        $products = $productCartRepository->findBy(['cart' => $cart]);
        $prix_total = $user->getcart()->getPrixTotal() + 10;
        return $this->render('pages/cart/index.html.twig', [
            'products' => $products,
            'prixtotal' => $prix_total
        ]);
    }

    #[Route('/removeArticle/{id}', name: 'cart.remove')]
    public function removeArticle(ProductCartRepository $productCartRepository, $id, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $product = $productCartRepository->find($id);
        $user = $userRepository->find($this->getUser()->getId());
        $user->getCart()->setPrixTotal($user->getcart()->getPrixTotal() - $product->getProduct()->getPrix());
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('cart');

    }
}
