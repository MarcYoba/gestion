<?php
// src/Service/StockCheckerService.php
namespace App\Service;

use App\Entity\BondCommandeA;
use App\Entity\ProduitA;

use Doctrine\ORM\EntityManagerInterface;

class StockCheckerService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function checkAndCreateOrder(): void
    {
        // Récupérer les produits avec quantité = 0
        $products = $this->entityManager
            ->getRepository(ProduitA::class)
            ->findAll();

        if (empty($products)) {
            return;
        }
        // Créer un nouveau bon de commande
        foreach ($products as $product) {
            $bonCommand = $this->entityManager->getRepository(BondCommandeA::class)->findOneBy(['produit' => $product]);
            if ($product->getQuantite() > $bonCommand->getLimite() ) {
                $bonCommand->setStatut(1);
            }else{
                $bonCommand->setStatut(0);
            }
            $this->entityManager->persist($bonCommand);
        }
        $this->entityManager->flush();
    }
}