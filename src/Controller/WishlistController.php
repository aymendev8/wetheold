<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'wishlist')]
    public function index(): Response
    {
        $wishlist = $this->getUser()->getWishlist();
        return $this->render('pages/wishlist/index.html.twig', [
            'controller_name' => 'WishlistController',
        ]);
    }
    #[Route('/{name}/addwishlist', name: 'wishlist.add')]
    public function add(Articles $article, UserRepository $userRepository, EntityManagerInterface $manager, ): Response
    {
        if ($this->getUser() != null) {
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            //dump($user->getWishlist());die;
            $wishlist =
            $wishlist = $user->getWishlist();
            $wishlist->addProduct($article);
            $manager->flush();
        }
        return $this->redirectToRoute('wishlist', ['name' => $article->getName()]);
    }
}
