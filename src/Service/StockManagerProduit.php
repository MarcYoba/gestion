<?php
// src/Service/StockManager.php
namespace App\Service;

use App\Entity\BondCommande;
use App\Entity\Magasin;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;

class StockManagerProduit
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function verifierLimiteCommande(Produit $produit): void
    {
        // On vérifie si le produit appartient au MagasinA
        // dump($produit->getId());
        $magasin = $this->entityManager->getRepository(Magasin::class)->findOneBy(['produit' => $produit]);
        
        if (!$magasin) {
            // dump('produit non trouver au magasin, on continue');
            return;
            
        }
        // dump('On vérifie si le produit appartient au MagasinA, on continue');
        $bonCommande = $this->entityManager->getRepository(BondCommande::class)->findOneBy(['produit' => $produit]);
        if (!$bonCommande) {
            return;
        }

        // Logique de comparaison
        // dump('Logique de comparaison, on continue');
        $quantite = $magasin->getQuantite() + $produit->getQuantite();
        if ( $quantite <= $bonCommande->getLimite()) {
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