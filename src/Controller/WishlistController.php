<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'wishlist')]
    public function index(WishlistRepository $wishlistRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        return $this->render('pages/wishlist/index.html.twig', [
            'wishlist' => $wishlistRepository->findOneBy(['user' => $this->getUser()]),
        ]);
    }
    #[Route('/{name}/addwishlist', name: 'wishlist.add')]
    public function add(Articles $article, WishlistRepository $wishlistRepository, EntityManagerInterface $manager, ): Response
    {
        $wishlist = $wishlistRepository->findOneBy(['user' => $this->getUser()]);
        if ($this->getUser() != null && !$wishlist->getProducts()->contains($article)) {
            $wishlist->addProduct($article);
            $manager->flush();
            return $this->redirectToRoute('wishlist');
        }
        return $this->redirectToRoute('wishlist');
    }

    #[Route('/{name}/removewishlist', name: 'wishlist.remove')]
    public function remove(Articles $article, WishlistRepository $wishlistRepository, EntityManagerInterface $manager, ): Response
    {
        if ($this->getUser() != null) {
            $wishlist = $wishlistRepository->findOneBy(['user' => $this->getUser()]);
            $wishlist->removeProduct($article);
            $manager->flush();
            return $this->redirectToRoute('wishlist');
        }
        return $this->redirectToRoute('security.login');
    }
}
