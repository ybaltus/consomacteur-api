<?php

namespace App\Entity;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\HydraulicRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HydraulicRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            controller: NotFoundAction::class,
            output: false,
            read: false
        ),
        new GetCollection(
            order: [
                'measureDate' => 'ASC',
            ]),
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'region' => 'exact',
        'codeInsee' => 'exact',
    ]
)]
#[ApiFilter(
    DateFilter::class,
    properties: [
        'measureDate',
    ]
)]
class Hydraulic extends SingleEnergyTypeAbstract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
