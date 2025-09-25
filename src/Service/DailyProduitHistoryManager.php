<?php
// src/Service/DailySalesHistoryManager.php
namespace App\Service;

use App\Entity\Historique;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class DailyProduitHistoryManager
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private string $historyFilePath;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger,KernelInterface $kernel)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->historyFilePath = $kernel->getProjectDir() . '/public/historique/daily_Produit_history.json';
    }

    public function shouldGenerateHistory(): bool
    {
        $now = new \DateTime();
        $currentHour = (int) $now->format('H');
        
        // Vérifie si on est dans la plage horaire (23h-04h)
        if (!($currentHour >= 23 || $currentHour < 04)) {
            return false;
        }

        $today = $now->format('Y-m-d');
        $historyData = $this->getHistoryData();

        // Vérifie si l'historique a déjà été généré aujourd'huiservices:

        if (isset($historyData[$today]) && $historyData[$today] === true) {
            return false;
        }

        return true;
    }

    public function generateDailyHistory(): void
    {
        if (!$this->shouldGenerateHistory()) {
            return;
        }

        $historiquesCrees = 0;
        $dateHier = new \DateTime();

        try {
            // 1. Récupérer les produits
            $produit = $this->em->getRepository(Produit::class)->findAll();

            // 2. Créer l'historique
            foreach ($produit as  $value) {
                $history = new Historique();
                $history->setProduit($this->em->getRepository(Produit::class)->find($value->getId()));
                $history->setAgance($value->getAgence());
                $history->setQuantite($value->getQuantite());
                $history->setCreatedAt($dateHier);
                $history->setHeurecameroun(date("H:i:s"));
                $history->setHeureserver(date("H:i:s"));

                // 3. Sauvegarder
                $this->em->persist($history);
                $historiquesCrees++;
            }
            
            $this->em->flush();

            // 4. Marquer comme généré
            $this->markAsGenerated();

            $this->logger->info("Historique généré : {$historiquesCrees} produits");

        } catch (\Exception $e) {
            $this->logger->error('Erreur génération historique: ' . $e->getMessage());
        }
    }

    private function getHistoryData(): array
    {
        if (!file_exists($this->historyFilePath)) {
            return [];
        }

        return json_decode(file_get_contents($this->historyFilePath), true) ?? [];
    }

    private function markAsGenerated(): void
    {
        $historyData = $this->getHistoryData();
        $historyData[date('Y-m-d')] = true;
        
        // Nettoyer les anciennes entrées (30 jours)
        $thirtyDaysAgo = new \DateTime('-30 days');
        foreach ($historyData as $date => $value) {
            if (new \DateTime($date) < $thirtyDaysAgo) {
                unset($historyData[$date]);
            }
        }

        file_put_contents($this->historyFilePath, json_encode($historyData));
    }
}