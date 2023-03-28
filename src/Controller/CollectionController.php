<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\ProductCart;
use App\Repository\ArticlesRepository;
use App\Repository\SizeRepository;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{
    #[Route('/collection', name: 'collection.index', methods: ['GET', 'POST'])]
    public function index(ArticlesRepository $repository, WishlistRepository $wishlistRepository, Request $request): Response
    {
        if ($this->getUser() != null) {
            $wishlist = $wishlistRepository->findOneBy(['user' => $this->getUser()]);
        } else {
            $wishlist = null;
        }
        if ($request->isMethod('POST') && $request->request->get('search') != null) {
            $search = $request->request->get('search');
            $name = $repository->findNameKeyword($search);
            $marque = $repository->findmarqueKeyword($search);
            $tmp = array_merge($name, $marque);
            $articles = array_unique($tmp, SORT_REGULAR);
        }
        return $this->render('pages/collection/index.html.twig', [
            'articles' => $articles ?? $repository->findAll(),
            'wishlist' => $wishlist,
        ]);
    }
    #[Route('/collection/{name}', name: 'collection.show')]
    public function show(ArticlesRepository $repository,Articles $article, SizeRepository $sizeRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $manager,WishlistRepository $wishlistRepository): Response
    {

        if ($request->isMethod('POST') && $this->getUser() != null && $request->request->get('size') != null) {
            $size = $request->request->get('size');
            $quantity = 1;
            $productCart = new ProductCart();
            $productCart->setProduct($article);
            $productCart->setQuantite($quantity);
            $productCart->setTaille($size);
            $productCart->setCart($userRepository->find($this->getUser()->getId())->getcart());
            $userRepository->find($this->getUser()->getId())->getcart()->setPrixTotal($userRepository->find($this->getUser()->getId())->getcart()->getPrixTotal() + ($article->getPrix()));
            $manager->persist($productCart);
            $manager->flush();
            $this->addFlash('success', 'Votre article a bien été ajouté au panier');
        }
        //Si pas d'ajout au panier
        $wishlist = null;
        if ($this->getUser() != null) {
            $wishlist = $wishlistRepository->findOneBy(['user' => $this->getUser()]);
        }
        if ($request->isMethod('POST') && $request->request->get('search') != null) {
            $search = $request->request->get('search');
            $name = $repository->findNameKeyword($search);
            $marque = $repository->findmarqueKeyword($search);
            $tmp = array_merge($name, $marque);
            $articles = array_unique($tmp, SORT_REGULAR);
            return $this->render('pages/collection/index.html.twig', [
                'articles' => $articles ?? $repository->findAll(),
                'wishlist' => $wishlist,
            ]);
        }
        return $this->render('pages/collection/article.html.twig', [
            'article' =>  $article,
            'sizes' =>  $sizeRepository->findAll()
        ]);
    }
}
