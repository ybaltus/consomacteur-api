<?php

namespace App\EventListener;

use App\Entity\EnergyConsumption;
use App\Entity\EnergyType;
use App\Entity\Region;

abstract class EntityListenerAbstract
{
    public const ENTITIES = [
        EnergyType::class,
        Region::class,
        EnergyConsumption::class,
    ];

    /**
     * Verify if the entity is in the constant ENTITIES.
     */
    public function checkInstanceOf(object $entity): bool
    {
        foreach (self::ENTITIES as $className) {
            if ($entity instanceof $className) {
                return true;
            }
        }

        return false;
    }
}
