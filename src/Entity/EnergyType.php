<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\EnergyTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnergyTypeRepository::class)]
#[UniqueEntity('nameSlug')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            order: [
            'nameSlug' => 'ASC',
        ]),
    ],
    normalizationContext: [
        'groups' => [
            'energyType:read',
        ],
    ]
)]
#[ApiFilter(
    BooleanFilter::class,
    properties: [
        'isLocked' => false,
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: [
        'nameSlug' => 'ASC',
    ]
)]
class EnergyType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('energyType:read')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 100
    )]
    #[Groups('energyType:read')]
    private string $name;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 2,
        max: 100
    )]
    #[Groups('energyType:read')]
    private string $nameSlug;

    #[ORM\Column]
    #[Groups('energyType:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('energyType:read')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type('boolean')]
    #[Groups('energyType:read')]
    private bool $isLocked = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function setNameSlug(string $nameSlug): static
    {
        $this->nameSlug = $nameSlug;

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

    public function isIsLocked(): bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): static
    {
        $this->isLocked = $isLocked;

        return $this;
    }
}
