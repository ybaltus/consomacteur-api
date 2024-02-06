<?php

namespace App\Services;

use App\Entity\EnergyType;
use Doctrine\ORM\EntityManagerInterface;

final class InitDataService
{
    private const ENERTYPES = [
        'Électrique',
        'Thermique',
        'Nucléaire',
        'Éolien',
        'Solaire',
        'Hydraulique',
    ];

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function initEnergyTypes(): void
    {
        foreach (self::ENERTYPES as $name) {
            $entity = (new EnergyType())
                ->setName($name)
            ;
            $this->em->persist($entity);
        }

        $this->em->flush();
    }
}