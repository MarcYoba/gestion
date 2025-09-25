<?php

namespace App\EventListener;

use App\Service\DailyProduitHistoryManager;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SalesProduitListener
{
    private DailyProduitHistoryManager $historyManager;

    public function __construct(DailyProduitHistoryManager $historyManager)
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