<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(CommandesRepository $commandesRepository, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
        if (count($this->getUser()->getRoles()) == 1) {
            return $this->redirectToRoute('homepage');
        }
        $prix = $manager->createQuery(
            'SELECT sum(c.prixtotal) as total FROM App\Entity\Commandes c'
        );
        $nbcommandes = $manager->createQuery(
            'SELECT count(c.id) as nb FROM App\Entity\Commandes c'
        );

        $nb_articles = $manager->createQuery(
            'SELECT count(a.id) as nb FROM App\Entity\Articles a'
        );

        $nb_utilisateurs = $manager->createQuery(
            'SELECT count(u.id) as nb FROM App\Entity\User u'
        );
        return $this->render('pages/admin/index.html.twig', [
            'nbcommandes' => $nbcommandes->getResult()[0]['nb'] ,
            'total' => $total = $prix->getResult()[0]['total'],
            'nb_articles' => $nb_articles->getResult()[0]['nb'],
            'nb_utilisateurs' => $nb_utilisateurs->getResult()[0]['nb'],
            'commandes' => $commandesRepository->findAll(),
        ]);
    }
}
