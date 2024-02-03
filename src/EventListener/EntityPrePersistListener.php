<?php

namespace App\EventListener;

use App\Entity\EnergyType;
use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
final class EntityPrePersistListener extends EntityListenerAbstract
{
    public function __construct(
        private AsciiSlugger $slugger = new AsciiSlugger()
    ) {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // Check the entity instance
        $checkEntity = $this->checkInstanceOf($entity);
        if (!$checkEntity) {
            return;
        }

        $this->createSlug($entity);
        $this->setCreatedAtValue($entity);
    }

    /**
     * Create a slug with String component.
     */
    private function createSlug(object $entity): void
    {
        $hasNameSlug = method_exists($entity, 'setNameSlug');

        if (!$hasNameSlug) {
            return;
        }

        /**
         * @var EnergyType|Region $entity
         */
        $entity->setNameSlug($this->slugger->slug($entity->getName())->lower());
    }

    private function setCreatedAtValue(object $entity): void
    {
        $entity->setCreatedAt(new \DateTimeImmutable());
    }
}
