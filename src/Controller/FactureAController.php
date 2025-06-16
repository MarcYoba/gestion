<?php

namespace App\Controller;

use App\Entity\FactureA;
use App\Entity\VenteA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureAController extends AbstractController
{
    #[Route('/facture/a/view/{id}', name: 'app_facture_a')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $vente = $em->getRepository(VenteA::class)->find($id);
        $facture = $em->getRepository(FactureA::class)->findBy(["vente"=>$vente]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }
        return $this->render('facture_a/index.html.twig', [
            'facture' => $facture,
            'id' => $id,
            'client' => $client,
            'vente' => $vente,
        ]);
    }
}
