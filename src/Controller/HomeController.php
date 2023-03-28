<?php

    namespace App\Controller;


    use App\Repository\ArticlesRepository;
    use App\Repository\WishlistRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
        public function index(ArticlesRepository $repository, WishlistRepository $wishlistRepository, Request $request) : Response
        {
            $newarticles = $repository->findBy([], ['id' => 'DESC'], 4);
            $bestarticles = $repository->findBy([], ['id' => 'ASC'], 4);
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
           return $this->render('home.html.twig', [
               'articles' => $newarticles,
               'bestarticles' => $bestarticles,
               'wishlist' => $wishlist,
           ]);
        }
    }
