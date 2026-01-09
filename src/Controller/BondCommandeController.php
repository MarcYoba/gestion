<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BondCommandeController extends AbstractController
{
    #[Route('/bond/commande', name: 'app_bond_commande')]
    public function index(): Response
    {
        return $this->render('bond_commande/index.html.twig', [
            'controller_name' => 'BondCommandeController',
        ]);
    }
}
