<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'articles.read')]
    public function read(ArticlesRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $articles = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('pages/articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
