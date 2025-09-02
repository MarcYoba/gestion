<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Entity\TempAgence;
use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_historique')]
    public function index(EntityManagerInterface $em): Response
    {
        $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        $client = $em->getRepository(Clients::class)->findAll(["agence"=> $id]);

        $historique = $em->getRepository(Historique::class)->findAll(["agance"=> $id]);

        return $this->render('historique/index.html.twig', [
            'client' => $client,
            'historiques'=> $historique
        ]);
    }
}
