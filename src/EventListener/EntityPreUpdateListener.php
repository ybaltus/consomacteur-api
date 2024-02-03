<?php

namespace App\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]
final class EntityPreUpdateListener extends EntityListenerAbstract
{
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        // Check the entity instance
        $checkEntity = $this->checkInstanceOf($entity);
        if (!$checkEntity) {
            return;
        }

        $this->setUpdatedAtValue($entity);
    }

    private function setUpdatedAtValue(object $entity): void
    {
        $entity->setUpdatedAt(new \DateTimeImmutable());
    }
}
