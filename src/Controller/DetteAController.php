<?php

namespace App\Controller;

use App\Entity\TempAgence;
use App\Entity\VenteA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetteAController extends AbstractController
{
    #[Route('/dette/a/creance', name: 'app_dette_a')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence();

        $dette = $em->getRepository(VenteA::class)->findRapportVenteToCredit($agence);

        return $this->render('dette_a/index.html.twig', [
            'dettes' => $dette,
        ]);
    }
}
