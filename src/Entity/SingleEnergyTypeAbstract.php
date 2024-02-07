<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

abstract class SingleEnergyTypeAbstract
{
    #[ORM\Column]
    #[Assert\PositiveOrZero]
    protected int $codeInsee;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 100
    )]
    protected string $region;

    #[ORM\Column]
    protected \DateTimeImmutable $measureDate;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    protected int $measureValue;

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

    public function getMeasureValue(): int
    {
        return $this->measureValue;
    }

    public function setMeasureValue(int $measureValue): static
    {
        $this->measureValue = $measureValue;

        return $this;
    }
}
