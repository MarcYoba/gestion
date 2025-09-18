<?php
// src/Service/ProductHistoryService.php

namespace App\Service;

use App\Entity\ProduitA;
use App\Entity\HistoriqueA;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ProductHistoryService
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @CronJob("* * * * *")
     */
    public function saveDailyHistory(): bool
    {
        try {
            $products = $this->entityManager->getRepository(ProduitA::class)->findAll();
            $date = new \DateTime();
            $dateTime = new \DateTime('now', new \DateTimeZone('Africa/Douala'));
            $dateTime = $dateTime->format('H-i-s');
            foreach ($products as $product) {
                $history = new HistoriqueA();
                $history->setProduitA($product);
                $history->setQuantite($product->getQuantite());
                $history->setCreatetAd(new \DateTime());
                $history->setHeurecameroun($dateTime);
                $history->setHeureserver(date("H-i-s"));
                $history->setAgence($product->getAgence());
                
                $this->entityManager->persist($history);
            }
            
            $this->entityManager->flush();
            $this->logger->info(sprintf('Historique sauvegardé pour %d produits', count($products)));
            
            return true;
            
        } catch (\Exception $e) {
            $this->logger->error('Erreur ProductHistoryService: ' . $e->getMessage());
            return false;
        }
    }
}