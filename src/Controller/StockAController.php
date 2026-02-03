<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\FactureA;
use App\Entity\HistoriqueA;
use App\Entity\MagasinA;
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
        $date = date("Y"."-01"."-04");
        
        $produits = [];
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);
        foreach ($produit as $key => $value) {
            $historiquequatite = 0;
            $historiques = $em->getRepository(HistoriqueA::class)->findByProduitAgence($value,$id,$date);
            $sommeachat = $em->getRepository(AchatA::class)->findBySommeAchatProduitDay($date,$value,$id);
            $sommeventejour = $em->getRepository(FactureA::class)->findByQuantiteProduitVendu(new \DateTimeImmutable(), $value->getid(), $id);
            $sommevente = $em->getRepository(FactureA::class)->findByQuantiteProduitVenduAnne($date, $value, $id);
            $magasinQt = $em->getRepository(MagasinA::class)->findOneBy(['produit' => $value, 'agence' => $id]);
            if ($historiques) {
                $historiquequatite = $historiques->getQuantite();
            }
            array_push($produits, [$value->getNom(),$historiquequatite, $sommeachat,$sommeventejour,$sommevente,$value->getQuantite(),empty($magasinQt)?0:$magasinQt->getQuantite()]);
        }
        
        return $this->render('stock_a/recapitulatif.html.twig', [
            'produits' => $produit,
            'produitsData' => $produits,

        ]);
    }

    #[Route('/stock/a/perte', name: 'app_stock_a_perte')]
    public function perte(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $date = date("Y"."-01"."-04");
        
        $produits = [];
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);
        foreach ($produit as $key => $value) {
            $historiquequatite = 0;
            $historiques = $em->getRepository(HistoriqueA::class)->findByProduitAgence($value,$id,$date);
            $sommeachat = $em->getRepository(AchatA::class)->findBySommeAchatProduitDay($date,$value,$id);
            $sommeventejour = $em->getRepository(FactureA::class)->findByQuantiteProduitVendu(new \DateTimeImmutable(), $value->getid(), $id);
            $sommevente = $em->getRepository(FactureA::class)->findByQuantiteProduitVenduAnne($date, $value, $id);
            $magasinQt = $em->getRepository(MagasinA::class)->findOneBy(['produit' => $value, 'agence' => $id]);
            if ($historiques) {
                $historiquequatite = $historiques->getQuantite();
            }
            $stocktreel = $value->getQuantite() + (empty($magasinQt)?0:$magasinQt->getQuantite());
            array_push($produits, [$value->getNom(),$historiquequatite + $sommeachat,$sommevente,$stocktreel,$value->getPrixvente()]);
        }
        
        return $this->render('stock_a/perte.html.twig', [
            'produits' => $produit,
            'produitsData' => $produits,

        ]);
    }
}
