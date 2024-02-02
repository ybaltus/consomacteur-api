<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EnergyConsumptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnergyConsumptionRepository::class)]
#[ApiResource]
class EnergyConsumption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private EnergyType $energyType;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Region $region;

    #[ORM\Column(nullable: true)]
    private ?float $measure_value = null;

    #[ORM\Column]
    private \DateTimeImmutable $measureDate;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
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
