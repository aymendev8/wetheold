<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
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
    public function show(Articles $article): Response
    {
        //TODO : Tester s'il y a des données en POST

        //TOdo: Envoyer les données à cart_product

        // ToDO : Rediriger vers le panier

        //Si pas d'ajout au panier
        return $this->render('pages/collection/article.html.twig', [
            'article' => $article,
        ]);
    }
}
