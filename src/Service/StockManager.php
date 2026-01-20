<?php
// src/Service/StockManager.php
namespace App\Service;

use App\Entity\BondCommandeA;
use App\Entity\MagasinA;
use App\Entity\ProduitA;
use Doctrine\ORM\EntityManagerInterface;

class StockManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function verifierLimiteCommande(ProduitA $produit): void
    {
        // On vérifie si le produit appartient au MagasinA
        // dump($produit->getId());
        $magasin = $this->entityManager->getRepository(MagasinA::class)->findOneBy(['produit' => $produit]);
        
        if (!$magasin) {
            // dump('produit non trouver au magasin, on continue');
            return;
            
        }
        // dump('On vérifie si le produit appartient au MagasinA, on continue');
        $bonCommande = $this->entityManager->getRepository(BondCommandeA::class)->findOneBy(['produit' => $produit]);
        if (!$bonCommande) {
            return;
        }

        // Logique de comparaison
        // dump('Logique de comparaison, on continue');
        if ($magasin->getQuantite() > $bonCommande->getLimite()) {
            $bonCommande->setStatut(1);
            // dump('superier, on continue');
        } else {
            $bonCommande->setStatut(0);
            //$bonCommande->setQuantite($magasin->getQuantite());
            // dump('inferieur, on continue');
        }

        // On sauvegarde la modification du Bon de Commande
        // dump('On sauvegarde la modification du Bon de Commande, on continue');
        $this->entityManager->persist($bonCommande);
        $this->entityManager->flush();
    }
}
?>