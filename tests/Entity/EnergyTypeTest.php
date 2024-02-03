<?php

namespace App\Tests\Entity;

use App\Entity\EnergyType;
use App\Tests\Trait\AppTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EnergyTypeTest extends KernelTestCase implements EntityTestInterface
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
        return (new EnergyType())
            ->setName($title)
            ->setNameSlug($title.'_slug')
            ;
    }

    public function testEntityIsValid(): void
    {
        /**
         * @var ValidatorInterface $validatorService
         */
        $validatorService = $this->initBootKernelContainer()->get('validator');
        $entity = $this->getEntity('EnergyTypeValid');
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

    public function testUniqueNameSlug(): void
    {
        /**
         * @var ValidatorInterface $validatorService
         */
        $validatorService = $this->initBootKernelContainer()->get('validator');
        $entity = $this->getEntity('Electrique');
        $entity->setNameSlug('electrique');
        $assertResults = $this->assertViolationsWithValidator($validatorService, $entity);
        $this->assertCount(1, $assertResults[0], $assertResults[1]);
    }
}
