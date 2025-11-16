<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Classe7Controller extends AbstractController
{
    #[Route('/classe7/vente', name: 'app_classe7_vente')]
    public function index(): Response
    {
        return $this->render('classe7/index.html.twig', [
            'controller_name' => 'Classe7Controller',
        ]);
    }
}
