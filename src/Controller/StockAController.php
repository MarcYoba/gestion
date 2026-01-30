<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\HistoriqueA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockAController extends AbstractController
{
    #[Route('/stock/a/recapitulatif', name: 'app_stock_a_recapitulatif')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $date = date("Y"."01"."04");
        
        $produits = [];
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);
        foreach ($produit as $key => $value) {
            $historiquequatite = 0;
            $historiques = $em->getRepository(HistoriqueA::class)->findByProduitAgence($value,$id,$date);
            if ($historiques) {
                $historiquequatite = $historiques->getQuantite();
            }
            array_push($produits, $value,$historiquequatite);
        }
        return $this->render('stock_a/recapitulatif.html.twig', [
            'produits' => $produit,

        ]);
    }
}
