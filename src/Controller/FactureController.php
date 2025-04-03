<?php

namespace App\Controller;

use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    #[Route('/facture/view/{id}', name: 'app_facture')]
    public function index(EntityManagerInterface $entityManger,int $id): Response
    {
        $facture = $entityManger->getRepository(Facture::class)->findBy(['vente'=>$id]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }
        return $this->render('facture/index.html.twig', [
            'facture' => $facture,
            'id' => $id,
            'client' => $client,
            'vente' => $vente,
        ]);
    }
}
