<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Vente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        return $this->render('home/index.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/home/dashboard/{id}', name: 'app_home_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager,int $id): Response
    {
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        //$sommevente = $entityManager->getRepository(Vente::class)->findTotalPriceByYear(date('Y'));
        return $this->render('home/dashboard.html.twig', [
            'agence' => $agence,
            //'sommevente' => $sommevente,
        ]);
    }

    #[Route('/home/dashboardA/{id}', name: 'app_home_dashboardA')]
    public function dashboardA(EntityManagerInterface $entityManager,int $id): Response
    {
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        return $this->render('home/dashboardA.html.twig', [
            'agence' => $agence,
        ]);
    }
}