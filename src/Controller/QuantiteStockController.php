<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Facture;
use App\Entity\Historique;
use App\Entity\Produit;
use App\Entity\TempAgence;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuantiteStockController extends AbstractController
{
    #[Route('/quantite/stock', name: 'app_quantite_stock')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(Produit::class)->findBy(["agence" => $id]);
        $quantiteStock = [];
        $date = date("Y").'-01-02';
        $date = new DateTime($date);
        foreach ($produit as $key => $value) {
            $historique = $em->getRepository(Historique::class)->findByDate($date,$value->getId(),$id);
            $achat = $em->getRepository(Achat::class)->findBySommeAchatProduit($date->format("Y"),$value->getId(),$id);
            $facture = $em->getRepository(Facture::class)->findBySommeProduit($date->format("Y"), $value->getId(),$id);
            array_push($quantiteStock,[$value->getNom(),$historique,$achat,$facture,$value->getQuantite()]);   
        }
        return $this->render('quantite_stock/index.html.twig', [
            'quantitestock' => $quantiteStock,
        ]);
    }
}
