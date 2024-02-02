<?php

namespace App\DataFixtures;

use App\Entity\EnergyConsumption;
use App\Entity\EnergyType;
use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    private const ENERTYPES = [
        'Électrique',
        'Thermique',
        'Nucléaire',
        'Éolien',
        'Solaire',
        'Hydraulique',
    ];

    private const REGIONS = [
        'Normandie',
        'Bourgogne-Franche-Comté',
        'Bretagne',
        'Île-de-France',
        'Nouvelle-Aquitaine',
        'Hauts-de-France',
        'Auvergne-Rhône-Alpes',
        'Occitanie',
        'Pays de la Loire',
        'Provence-Alpes-Côte d\'Azur',
        'Centre-Val de Loire',
        'Grand Est',
        'Île-de-France',
        'Bretagne',
        'Hauts-de-France',
    ];

    private const RANDDATES = [
      '2023-01-01',
      '2023-02-01',
      '2023-03-01',
      '2023-04-01',
      '2023-05-01',
      '2023-06-01',
    ];

    /**
     * @var EnergyType[]|array
     */
    private array $energyTypes = [];

    /**
     * @var Region[]|array
     */
    private array $regions = [];

    public function load(ObjectManager $manager): void
    {
        $this->addEnergyTypes($manager);
        $this->addRegions($manager);

        $manager->flush();

        $this->addEnergyConsumptions($manager);

        $manager->flush();
    }

    private function addEnergyConsumptions(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 25; ++$i) {
            $entity = (new EnergyConsumption())
                ->setEnergyType($this->energyTypes[mt_rand(0, count(self::ENERTYPES) - 1)])
                ->setRegion($this->regions[mt_rand(0, count(self::REGIONS) - 1)])
                ->setMeasureDate(new \DateTimeImmutable(self::RANDDATES[mt_rand(0, count(self::RANDDATES) - 1)]))
                ->setMeasureValue(mt_rand(1566, 9999))
            ;
            $manager->persist($entity);
        }
    }

    private function addEnergyTypes(ObjectManager $manager): void
    {
        foreach (self::ENERTYPES as $name) {
            $entity = (new EnergyType())
            ->setName($name)
            ;
            $manager->persist($entity);
            $this->energyTypes[] = $entity;
        }
    }

    private function addRegions(ObjectManager $manager): void
    {
        foreach (self::REGIONS as $name) {
            $entity = (new Region())
                ->setName($name)
                ->setCodeInsee(mt_rand(1, 50))
            ;
            $manager->persist($entity);
            $this->regions[] = $entity;
        }
    }
}
