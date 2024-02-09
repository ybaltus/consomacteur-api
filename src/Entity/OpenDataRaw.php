<?php

namespace App\Entity;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\OpenDataRawRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OpenDataRawRepository::class)]
#[ApiResource(
    routePrefix: '/opendata',
    operations: [
        new Get(
            controller: NotFoundAction::class,
            output: false,
            read: false
        ),
        new GetCollection(),
    ],
    normalizationContext: [
        'groups' => [
            'openDataRaw:read',
        ],
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'code_insee' => 'exact',
        'region' => 'partial',
    ]
)]
#[ApiFilter(
    DateFilter::class,
    properties: [
        'measureDate',
    ]
)]
class OpenDataRaw
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups('openDataRaw:read')]
    private int $codeInsee;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 100
    )]
    #[Groups('openDataRaw:read')]
    private string $region;

    #[ORM\Column]
    #[Groups('openDataRaw:read')]
    private \DateTimeImmutable $measureDate;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups('openDataRaw:read')]
    private int $consumElectric;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups('openDataRaw:read')]
    private int $consumThermic;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups('openDataRaw:read')]
    private int $consumNuclear;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups('openDataRaw:read')]
    private int $consumWind;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups('openDataRaw:read')]
    private int $consumSolar;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups('openDataRaw:read')]
    private int $consumHydraulic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeInsee(): int
    {
        return $this->codeInsee;
    }

    public function setCodeInsee(int $codeInsee): static
    {
        $this->codeInsee = $codeInsee;

        return $this;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getMeasureDate(): \DateTimeImmutable
    {
        return $this->measureDate;
    }

    public function setMeasureDate(\DateTimeImmutable $measureDate): static
    {
        $this->measureDate = $measureDate;

        return $this;
    }

    public function getConsumElectric(): int
    {
        return $this->consumElectric;
    }

    public function setConsumElectric(int $consumElectric): static
    {
        $this->consumElectric = $consumElectric;

        return $this;
    }

    public function getConsumThermic(): int
    {
        return $this->consumThermic;
    }

    public function setConsumThermic(int $consumThermic): static
    {
        $this->consumThermic = $consumThermic;

        return $this;
    }

    public function getConsumNuclear(): int
    {
        return $this->consumNuclear;
    }

    public function setConsumNuclear(int $consumNuclear): static
    {
        $this->consumNuclear = $consumNuclear;

        return $this;
    }

    public function getConsumWind(): int
    {
        return $this->consumWind;
    }

    public function setConsumWind(int $consumWind): static
    {
        $this->consumWind = $consumWind;

        return $this;
    }

    public function getConsumSolar(): int
    {
        return $this->consumSolar;
    }

    public function setConsumSolar(int $consumSolar): static
    {
        $this->consumSolar = $consumSolar;

        return $this;
    }

    public function getConsumHydraulic(): int
    {
        return $this->consumHydraulic;
    }

    public function setConsumHydraulic(int $consumHydraulic): static
    {
        $this->consumHydraulic = $consumHydraulic;

        return $this;
    }
}
