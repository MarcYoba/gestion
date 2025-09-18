<?php

// src/Command/ProductHistorySaveCommand.php

namespace App\Command;

use App\Service\ProductHistoryService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;

#[AsCommand(
    name: 'product:history-save',
    description: 'Sauvegarde l\'historique des produits tous les jours à 23h'
)]
class ProductHistorySaveCommand extends Command
{
    private ProductHistoryService $productHistoryService;
    private LoggerInterface $logger;

    public function __construct(ProductHistoryService $productHistoryService, LoggerInterface $logger)
    {
        parent::__construct();
        $this->productHistoryService = $productHistoryService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this->setDescription('Sauvegarde l\'historique des produits');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $io->info('Début de la sauvegarde de l\'historique des produits...');
            
            // Appel du service qui gère la logique métier
            $result = $this->productHistoryService->saveDailyHistory();
            
            if ($result) {
                $io->success('Historique des produits sauvegardé avec succès !');
                $this->logger->info('Historique des produits sauvegardé avec succès');
                return Command::SUCCESS;
            } else {
                $io->error('Erreur lors de la sauvegarde de l\'historique');
                $this->logger->error('Erreur lors de la sauvegarde de l\'historique des produits');
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $io->error('Erreur: ' . $e->getMessage());
            $this->logger->critical('Erreur critique dans product:history-save: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
