<?php

namespace App\Repository;

use App\Entity\EnergyConsumption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EnergyConsumption>
 *
 * @method EnergyConsumption|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnergyConsumption|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnergyConsumption[]    findAll()
 * @method EnergyConsumption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnergyConsumptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnergyConsumption::class);
    }

    //    /**
    //     * @return EnergyConsumption[] Returns an array of EnergyConsumption objects
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

    //    public function findOneBySomeField($value): ?EnergyConsumption
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
