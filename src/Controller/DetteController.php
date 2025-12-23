<?php

namespace App\Controller;

use App\Entity\TempAgence;
use App\Entity\Vente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetteController extends AbstractController
{
    #[Route('/dette/list', name: 'app_dette_list')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence();

        $dette = $em->getRepository(Vente::class)->findRapportVenteToCredit($agence);
        return $this->render('dette/index.html.twig', [
            'dettes' => $dette,
        ]);
    }
}
