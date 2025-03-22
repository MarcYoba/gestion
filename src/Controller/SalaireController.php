<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalaireController extends AbstractController
{
    #[Route('/salaire', name: 'app_salaire')]
    public function index(): Response
    {
        return $this->render('salaire/index.html.twig', [
            'controller_name' => 'SalaireController',
        ]);
    }
}
