<?php

namespace App\Entity;

use App\Repository\OpenDataRawRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpenDataRawRepository::class)]
class OpenDataRaw
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $codeInsee;

    #[ORM\Column(length: 150)]
    private string $region;

    #[ORM\Column]
    private \DateTimeImmutable $measureDate;

    #[ORM\Column]
    private int $consumElectric;

    #[ORM\Column]
    private int $consumThermic;

    #[ORM\Column]
    private int $consumNuclear;

    #[ORM\Column]
    private int $consumWind;

    #[ORM\Column]
    private int $consumSolar;

    #[ORM\Column]
    private int $consumHydraulic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeInsee(): int
    {
        return $this->codeInsee;
    }

    public function setCodeInsee(int $codeInsee): void
    {
        $this->codeInsee = $codeInsee;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function getMeasureDate(): \DateTimeImmutable
    {
        return $this->measureDate;
    }

    public function setMeasureDate(\DateTimeImmutable $measureDate): void
    {
        $this->measureDate = $measureDate;
    }

    public function getConsumElectric(): int
    {
        return $this->consumElectric;
    }

    public function setConsumElectric(int $consumElectric): void
    {
        $this->consumElectric = $consumElectric;
    }

    public function getConsumThermic(): int
    {
        return $this->consumThermic;
    }

    public function setConsumThermic(int $consumThermic): void
    {
        $this->consumThermic = $consumThermic;
    }

    public function getConsumNuclear(): int
    {
        return $this->consumNuclear;
    }

    public function setConsumNuclear(int $consumNuclear): void
    {
        $this->consumNuclear = $consumNuclear;
    }

    public function getConsumWind(): int
    {
        return $this->consumWind;
    }

    public function setConsumWind(int $consumWind): void
    {
        $this->consumWind = $consumWind;
    }

    public function getConsumSolar(): int
    {
        return $this->consumSolar;
    }

    public function setConsumSolar(int $consumSolar): void
    {
        $this->consumSolar = $consumSolar;
    }

    public function getConsumHydraulic(): int
    {
        return $this->consumHydraulic;
    }

    public function setConsumHydraulic(int $consumHydraulic): void
    {
        $this->consumHydraulic = $consumHydraulic;
    }
}
