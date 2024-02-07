<?php

namespace App\Repository;

use App\Entity\Solar;
use App\Repository\Trait\EnergyTypeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Solar>
 *
 * @method Solar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Solar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Solar[]    findAll()
 * @method Solar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolarRepository extends ServiceEntityRepository
{
    use EnergyTypeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Solar::class);
    }

    //    /**
    //     * @return Solar[] Returns an array of Solar objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Solar
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
