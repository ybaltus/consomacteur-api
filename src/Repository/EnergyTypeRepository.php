<?php

namespace App\Repository;

use App\Entity\EnergyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EnergyType>
 *
 * @method EnergyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnergyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnergyType[]    findAll()
 * @method EnergyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnergyTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnergyType::class);
    }

    //    /**
    //     * @return EnergyType[] Returns an array of EnergyType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    /**
     * @return EnergyType[]
     *
     * @throws QueryException
     */
    public function getAllWithNameSlugIndex(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.isLocked = FALSE')
            ->orderBy('e.nameSlug')
            ->indexBy('e', 'e.nameSlug')
            ->getQuery()
            ->getResult()
        ;
    }
}
