<?php
namespace App\Command;

use App\Service\DailyProduitAHistoryManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:generate-history', description: 'Génère l’historique quotidien des produits')]
class GenerateHistoryCommand extends Command
{
    private $historyManager;

    public function __construct(DailyProduitAHistoryManager $historyManager)
    {
        parent::__construct();
        $this->historyManager = $historyManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->historyManager->generateDailyHistory();
        $output->writeln('Tentative de génération de l’historique terminée.');
        return Command::SUCCESS;
    }
}