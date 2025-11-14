<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Classe2Controller extends AbstractController
{
    #[Route('/classe2/immobilisatiion/incorporelles', name: 'app_classe2_immobilisatiion')]
    public function index(): Response
    {
        return $this->render('classe2/immobilisation.html.twig', [
            'controller_name' => 'Classe2Controller',
        ]);
    }

    #[Route('/classe2/immobilisatiion/terrains', name: 'app_classe2_terrains')]
    public function terrains(): Response
    {
        return $this->render('classe2/terrains.html.twig', [
            'controller_name' => 'Classe2Controller',
        ]);
    }

    #[Route('/classe2/immobilisatiion/batiments', name: 'app_classe2_batiments')]
    public function batiments(): Response
    {
        return $this->render('classe2/terrains.html.twig', [
            'controller_name' => 'Classe2Controller',
        ]);
    }
}
