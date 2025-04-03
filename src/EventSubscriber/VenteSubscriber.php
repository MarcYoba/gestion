<?php

// src/EventSubscriber/VenteSubscriber.php
namespace App\EventSubscriber;

use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Entity\Vente;
use App\Entity\Credit;

class VenteSubscriber implements EventSubscriberInterface
{
    // Déclare les événements à écouter
    public static function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'onPrePersist',
        ];
    }

    // Méthode exécutée avant la persistance d'une entité
    public function onPrePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // Vérifie si l'entité est une Vente et de type "crédit"
        if ($entity instanceof Vente && $entity->getType() === 'credit') {
            $credit = new Credit();
            $credit->setVente($entity);
            $credit->setClient($entity->getClient());
            // Associe le crédit à la vente
            $entity->setCredit($credit);

            // Persiste le crédit
            $entityManager = $args->getObjectManager();
            $entityManager->persist($credit);
        }
    }
}