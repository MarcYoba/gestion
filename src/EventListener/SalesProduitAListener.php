<?php

namespace App\EventListener;

use App\Service\DailyProduitAHistoryManager;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SalesProduitAListener
{
    private DailyProduitAHistoryManager $historyManager;

    public function __construct(DailyProduitAHistoryManager $historyManager)
    {
        $this->historyManager = $historyManager;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        // Génère l'historique à chaque requête (vérification interne des conditions)
        $this->historyManager->generateDailyHistory();
    }
}