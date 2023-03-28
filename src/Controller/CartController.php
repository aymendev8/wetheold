<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Repository\CartRepository;
use App\Repository\CommandesRepository;
use App\Repository\ProductCartRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'cart')]
    public function index( UserRepository $userRepository, ProductCartRepository $productCartRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        $product = $productCartRepository->find($id);
        $user = $userRepository->find($this->getUser()->getId());
        $user->getCart()->setPrixTotal($user->getcart()->getPrixTotal() - $product->getProduct()->getPrix());
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('cart');

    }
    #[Route('checkout', name: 'cart.checkout')]
    public function checkout(Request $request, UserRepository $userRepository, EntityManagerInterface $manager, CommandesRepository $commandesRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        if ($request->isMethod('POST')){
            $commande = new Commandes();
            $user = $userRepository->findOneBy(['id' => $this->getUser()->getId()]);
            $cart = $user->getcart();
            $products_cards = $cart->getProduct();
            $commande->addUser($userRepository->find($this->getUser()));
            $commande->setEmail($request->request->get('email'));
            $commande->setFullname($request->request->get('fullname'));
            $commande->setVille($request->request->get('ville'));
            $commande->setAdresse($request->request->get('addresse'));
            $commande->setCodePostal($request->request->get('codepostal'));
            foreach ($products_cards as $products_card) {
                $commande->addArticle($products_card->getProduct());
            }
            $commande->setPrixTotal($cart->getPrixTotal());
            $manager->persist($commande);

            for ($i = 0; $i < count($products_cards); $i++) {
                $manager->remove($products_cards[$i]);
            }
            $user->getcart()->setPrixTotal(0);

            $manager->flush();

            return $this->redirectToRoute('cart.confirmation', ['id' => $commande->getId()]);
        }
        if ($commandesRepository->findAll() != null) {
            $commande = $commandesRepository->findAll()[count($commandesRepository->findAll()) - 1];
        } else {
            $commande = null;
        }
        return $this->render('pages/cart/checkout.html.twig', [
            'cart' => $this->getUser()->getCart(),
            'commande' =>$commande,
        ]);
    }
    #[Route('checkout/confirmation/{id}', name: 'cart.confirmation')]
    public function confirmation(Commandes $commande,CommandesRepository $commandesRepository): Response
    {
        $commande = $commandesRepository->findOneBy(['id' => $commande->getId()]);
        $user = $commande->getUser()[0];
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        if(($this->getUser()->getUserIdentifier() != $user->getUserIdentifier())){
            return $this->redirectToRoute('homepage');
        }
        return $this->render('pages/cart/detail.html.twig', [
            'commande' => $commande,
        ]);
    }
}
