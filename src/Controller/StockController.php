<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Facture;
use App\Entity\Historique;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    #[Route('/stock', name: 'app_stock')]
    public function index(): Response
    {
        return $this->render('stock/index.html.twig', [
            'controller_name' => 'StockController',
        ]);
    }
    #[Route('/stock/recapitulatif', name: 'app_stock_recapitulatif')]
    public function recapitulatif(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $date = date("Y"."-01"."-04");
        
        $produits = [];
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(Produit::class)->findBy(["agence" => $id]);
        foreach ($produit as $key => $value) {
            $historiquequatite = 0;
            $historiques = $em->getRepository(Historique::class)->findByProduitAgence($value,$id,$date);
            $sommeachat = $em->getRepository(Achat::class)->findBySommeAchatProduitDay($date,$value,$id);
            $sommeventejour = $em->getRepository(Facture::class)->findByQuantiteProduitVendu(new \DateTimeImmutable(), $value->getid(), $id);
            $sommevente = $em->getRepository(Facture::class)->findByQuantiteProduitVenduAnne($date, $value, $id);
            $magasinQt = $em->getRepository(Magasin::class)->findOneBy(['produit' => $value, 'agence' => $id]);
            if ($historiques) {
                $historiquequatite = $historiques->getQuantite();
            }
            array_push($produits, [$value->getNom(),$historiquequatite, $sommeachat,$sommeventejour,$sommevente,$value->getQuantite(),empty($magasinQt)?0:$magasinQt->getQuantite()]);
        }
        
        return $this->render('stock/recapitulatif.html.twig', [
            'produits' => $produit,
            'produitsData' => $produits,

        ]);
    }

    #[Route('/stock/recapitulatif/direction', name: 'app_stock_recapitulatif_direction')]
    public function recapitulatifDirection(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $date = date("Y"."-01"."-04");
        
        $produits = [];
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(Produit::class)->findAll();
        foreach ($produit as $key => $value) {
            $historiquequatite = 0;
            $historiques = $em->getRepository(Historique::class)->findByProduitAgenceAll($value,$date);
            $sommeachat = $em->getRepository(Achat::class)->findBySommeAchatProduitDayAll($date,$value);
            $sommeventejour = $em->getRepository(Facture::class)->findByQuantiteProduitVenduAll(new \DateTimeImmutable(), $value->getid());
            $sommevente = $em->getRepository(Facture::class)->findByQuantiteProduitVenduAnneAll($date, $value);
            $magasinQt = $em->getRepository(Magasin::class)->findOneBy(['produit' => $value, 'id' => "ASC"]);
            if ($historiques) {
                $historiquequatite = $historiques->getQuantite();
            }
            array_push($produits, [$value->getNom(),$historiquequatite, $sommeachat,$sommeventejour,$sommevente,$value->getQuantite(),empty($magasinQt)?0:$magasinQt->getQuantite(),$value->getAgence()->getNom()]);
        }
        
        return $this->render('stock/recapitulatif_direction.html.twig', [
            'produits' => $produit,
            'produitsData' => $produits,

        ]);
    }
}
