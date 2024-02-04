<?php

namespace App\Tests\Entity;

use App\Entity\OpenDataRaw;
use App\Tests\Trait\AppTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OpenDataRawTest extends KernelTestCase implements EntityTestInterface
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
        return (new OpenDataRaw())
            ->setMeasureDate($currentDate)
            ->setCodeInsee(10)
            ->setRegion($title)
            ->setConsumElectric(20)
            ->setConsumHydraulic(20)
            ->setConsumNuclear(10)
            ->setConsumSolar(56)
            ->setConsumWind(56)
        ;
    }

    public function testEntityIsValid(): void
    {
        $container = $this->initBootKernelContainer();
        /**
         * @var ValidatorInterface $validatorService
         */
        $validatorService = $container->get('validator');

        $entity = $this->getEntity('Bretagne');
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

        $assertResults = $this->assertViolationsWithValidator($validatorService, $entity);
        $this->assertCount(2, $assertResults[0], $assertResults[1]);
    }

    public function testConsumptionValuesZero(): void
    {
        /**
         * @var ValidatorInterface $validatorService
         */
        $validatorService = $this->initBootKernelContainer()->get('validator');
        $entity = $this->getEntity('Bretagne');
        $entity->setConsumElectric(0);
        $entity->setConsumHydraulic(0);
        $entity->setConsumNuclear(0);
        $entity->setConsumSolar(0);
        $entity->setConsumWind(0);

        $assertResults = $this->assertViolationsWithValidator($validatorService, $entity);
        $this->assertCount(0, $assertResults[0], $assertResults[1]);
    }
}
