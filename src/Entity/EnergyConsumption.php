<?php

namespace App\Entity;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\EnergyConsumptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnergyConsumptionRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            controller: NotFoundAction::class,
            output: false,
            read: false
        ),
        new GetCollection(
            order: [
                'region.nameSlug' => 'ASC',
            ]),
    ],
    normalizationContext: [
        'groups' => [
            'energyConsump:read',
        ],
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'energyType.nameSlug' => 'exact',
        'region.nameSlug' => 'exact',
    ]
)]
#[ApiFilter(
    DateFilter::class,
    properties: [
        'measureDate',
    ]
)]
class EnergyConsumption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(EnergyType::class)]
    private EnergyType $energyType;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(Region::class)]
    private Region $region;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Groups('energyConsump:read')]
    private ?float $measure_value = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Groups('energyConsump:read')]
    private \DateTimeImmutable $measureDate;

    #[ORM\Column]
    #[Groups('energyConsump:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('energyConsump:read')]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnergyType(): EnergyType
    {
        return $this->energyType;
    }

    public function setEnergyType(EnergyType $energyType): static
    {
        $this->energyType = $energyType;

        return $this;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getMeasureValue(): ?float
    {
        return $this->measure_value;
    }

    public function setMeasureValue(?float $measure_value): static
    {
        $this->measure_value = $measure_value;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
