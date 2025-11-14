<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Classe3Controller extends AbstractController
{
    #[Route('/classe3/marchandise', name: 'app_classe3_marchandise')]
    public function index(): Response
    {
        return $this->render('classe3/marchandise.html.twig', [
            'controller_name' => 'Classe3Controller',
        ]);
    }

    #[Route('/classe3/stock/route', name: 'app_classe3_stock_route')]
    public function Stock_route(): Response
    {
        return $this->render('classe3/stock_route.html.twig', [
            'controller_name' => 'Classe3Controller',
        ]);
    }

    #[Route('/classe3/stock/route', name: 'app_classe3_approviosionnements')]
    public function approviosionnements(): Response
    {
        return $this->render('classe3/approviosionnements.html.twig', [
            'controller_name' => 'Classe3Controller',
        ]);
    }

    #[Route('/classe3/produit/finis', name: 'app_classe3_produit_finis')]
    public function produit_finis(): Response
    {
        return $this->render('classe3/approviosionnements.html.twig', [
            'controller_name' => 'Classe3Controller',
        ]);
    }
}
