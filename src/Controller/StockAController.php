<?php

namespace App\Controller;

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
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);

        return $this->render('stock_a/recapitulatif.html.twig', [
            'produits' => $produit,
        ]);
    }
}
