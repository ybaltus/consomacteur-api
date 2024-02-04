<?php

namespace App\Tests\Entity;

use App\Entity\EnergyConsumption;
use App\Repository\EnergyTypeRepository;
use App\Repository\RegionRepository;
use App\Tests\Trait\AppTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EnergyConsumptionTest extends KernelTestCase implements EntityTestInterface
{
    use AppTestTrait;

    public function initBootKernelContainer(): ContainerInterface
    {
        // boot the Symfony kernel
        self::bootKernel();

        // use static::getContainer() to access the service container
        return static::getContainer();
    }

    public function getEntity(?string $title): object
    {
        $currentDate = new \DateTimeImmutable();
        return (new EnergyConsumption())
            ->setMeasureDate($currentDate)
            ->setMeasureValue(200)
        ;
    }

    public function testEntityIsValid(): void
    {
        $container = $this->initBootKernelContainer();
        /**
         * @var ValidatorInterface $validatorService
         */
        $validatorService = $container->get('validator');
        $energyTypeRepository = $container->get(EnergyTypeRepository::class);
        $regionRepository = $container->get(RegionRepository::class);

        $entity = $this->getEntity('');
        $entity->setEnergyType($energyTypeRepository->findOneBy(['isLocked' => false]));
        $entity->setRegion($regionRepository->findOneBy(['isLocked' => false]));

        $assertResults = $this->assertViolationsWithValidator($validatorService, $entity);
        $this->assertCount(0, $assertResults[0], $assertResults[1]);
    }

    public function testEntityIsInvalid(): void
    {
        /**
         * @var ValidatorInterface $validatorService
         */
        $validatorService = $this->initBootKernelContainer()->get('validator');
        $entity = $this->getEntity('');
        $entity->setMeasureValue(-1);

        $assertResults = $this->assertViolationsWithValidator($validatorService, $entity);
        $this->assertCount(1, $assertResults[0], $assertResults[1]);
    }

    public function testMeasureValueZero(): void
    {
        /**
         * @var ValidatorInterface $validatorService
         */
        $validatorService = $this->initBootKernelContainer()->get('validator');
        $entity = $this->getEntity('');
        $entity->setMeasureValue(0);

        $assertResults = $this->assertViolationsWithValidator($validatorService, $entity);
        $this->assertCount(0, $assertResults[0], $assertResults[1]);
    }
}
