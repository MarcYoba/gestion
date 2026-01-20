<?php
// src/EventListener/ProduitSubscriber.php
namespace App\EventListener;

use App\Entity\ProduitA;
use App\Service\StockManager;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProduitSubscriber implements EventSubscriberInterface
{
    private $stockManager;

    public function __construct(StockManager $stockManager)
    {
        $this->stockManager = $stockManager;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->traiterEntreeMagasin($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->traiterEntreeMagasin($args);
    }

    private function traiterEntreeMagasin(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        // dump(get_class($entity));
        // dump($entity instanceof ProduitA);
        // On n'agit que si c'est une entité Produit
        if (!$entity instanceof ProduitA) {
            // dump('Ce n\'est pas un ProduitA, on sort');
            return;
        }
        // dump('C\'est un ProduitA, on continue');
        $this->stockManager->verifierLimiteCommande($entity);
    }
}
?>