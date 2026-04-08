<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class EntrepriseAController extends AbstractController
{
    #[Route('/entreprise/a', name: 'app_entreprise_a')]
    public function index(ManagerRegistrY $doctrine): Response
    {
        // $entreprise = $doctrine->getConnection('secondary');

        // if ($entreprise) {
            
        // }else{

        // }

        return $this->render('entreprise_a/index.html.twig', [
            'controller_name' => 'EntrepriseAController',
        ]);
    }


}
