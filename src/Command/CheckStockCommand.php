<?php

namespace App\Command;

use App\Service\StockCheckerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:check-stock',
    description: 'Vérifie les stocks et met à jour le statut des bons de commande.',
)]
class CheckStockCommand extends Command
{
    private StockCheckerService $stockCheckerService;

    public function __construct(StockCheckerService $stockCheckerService)
    {
        parent::__construct();
        $this->stockCheckerService = $stockCheckerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Vérification du stock en cours...');

        try {
            $this->stockCheckerService->checkAndCreateOrder();
            $io->success('Les stocks ont été vérifiés et les bons de commande mis à jour avec succès.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Une erreur est survenue lors de la vérification : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}