<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Classe1Controller extends AbstractController
{
    #[Route('/classe1/capital', name: 'app_classe1_capital')]
    public function index(): Response
    {
        return $this->render('classe1/capital.html.twig', [
            'controller_name' => 'Classe1Controller',
        ]);
    }

    #[Route('/classe1/reserve', name: 'app_classe1_reserve')]
    public function reserve(): Response
    {
        return $this->render('classe1/reserve.html.twig', [
            'controller_name' => 'Classe1Controller',
        ]);
    }

    #[Route('/classe1/report/nouveau', name: 'app_classe1_report')]
    public function report_nouveau(): Response
    {
        return $this->render('classe1/report_nouveau.html.twig', [
            'controller_name' => 'Classe1Controller',
        ]);
    }
}
