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
            $newarticles = $repository->findBy([], ['id' => 'DESC'], 4);
            $bestarticles = $repository->findBy([], ['id' => 'ASC'], 4);
           return $this->render('home.html.twig', [
               'articles' => $newarticles,
                'bestarticles' => $bestarticles
           ]);
        }
    }
