<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/articles/modifier/{id}', name: 'articles.update')]
    public function update(Articles $article, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($article);
            $manager->flush();
            $this->addFlash('success', 'Votre article a bien été modifié');
            return $this->redirectToRoute('articles.read');
        }

        return $this->render('pages/articles/update.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }
    #[Route('/articles/supprimer/{id}', name: 'articles.delete')]
    public function delete(Articles $article, EntityManagerInterface $manager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $manager->remove($article);
        $manager->flush();
        $this->addFlash('success', 'Votre article a bien été supprimé');
        return $this->redirectToRoute('articles.read');
    }

    #[Route('/articles/ajouter', name: 'articles.create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        $article = new Articles();
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $manager->persist($article);
            $manager->flush();
            $this->addFlash('success', 'Votre article a bien été ajouté');
            return $this->redirectToRoute('articles.read');
        }

        return $this->render('pages/articles/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
