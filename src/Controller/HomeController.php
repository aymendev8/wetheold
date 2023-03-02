<?php

    namespace App\Controller;


    use App\Repository\ArticlesRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'homepage', methods: ['GET'])]
        public function index(ArticlesRepository $repository) : Response
        {
            $articles = $repository->findAll();
           return $this->render('home.html.twig', [
               'articles' => $articles
           ]);
        }
    }
