<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\FactureA;
use App\Entity\HistoriqueA;
use App\Entity\Produit;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuantiteStockAController extends AbstractController
{
    #[Route('/quantite/stock/a', name: 'app_quantite_stock_a')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);
        $quantiteStock = [];
        $date = date("Y").'-01-02';
        $date = new DateTime($date);
        foreach ($produit as $key => $value) {
            $historique = $em->getRepository(HistoriqueA::class)->findByDate($date,$value->getId(),$id);
            $achat = $em->getRepository(AchatA::class)->findBySommeAchatProduit($date->format("Y"),$value->getId(),$id);
            $facture = $em->getRepository(FactureA::class)->findBySommeProduit($date->format("Y"), $value->getId(),$id);
            array_push($quantiteStock,[$value->getNom(),$historique,$achat,$facture,$value->getQuantite()]);   
        }
        return $this->render('quantite_stock_a/index.html.twig', [
            'quantitestock' => $quantiteStock,
        ]);
    }
}
