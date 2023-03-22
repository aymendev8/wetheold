<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\ProductCart;
use App\Repository\ArticlesRepository;
use App\Repository\SizeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{
    #[Route('/collection', name: 'collection.index')]
    public function index(ArticlesRepository $repository): Response
    {

        $articles = $repository->findAll();
        return $this->render('pages/collection/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('/collection/{name}', name: 'collection.show')]
    public function show(Articles $article, SizeRepository $sizeRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $manager): Response
    {

        if ($request->isMethod('POST') && $this->getUser() != null) {
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
        return $this->render('pages/collection/article.html.twig', [
            'article' => $article,
            'sizes' =>  $sizeRepository->findAll()
        ]);
    }
}
