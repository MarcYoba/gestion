<?php

namespace App\Controller;

use App\Entity\HistoriqueA;
use App\Entity\TempAgence;
use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueAController extends AbstractController
{
    #[Route('/historique/a', name: 'app_historique_a')]
    public function index(EntityManagerInterface $em ): Response
    {
        $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        $client = $em->getRepository(Clients::class)->findAll();

        $historique = $em->getRepository(HistoriqueA::class)->findAll(["agance"=> $id]);

        return $this->render('historique_a/index.html.twig', [
            'client' => $client,
            'historiques'=> $historique
        ]);
    }
}
