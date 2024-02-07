<?php

namespace App\Repository;

use App\Entity\Electric;
use App\Repository\Trait\EnergyTypeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Electric>
 *
 * @method Electric|null find($id, $lockMode = null, $lockVersion = null)
 * @method Electric|null findOneBy(array $criteria, array $orderBy = null)
 * @method Electric[]    findAll()
 * @method Electric[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElectricRepository extends ServiceEntityRepository
{
    use EnergyTypeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Electric::class);
    }

    //    /**
    //     * @return Electric[] Returns an array of Electric objects
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

    //    public function findOneBySomeField($value): ?Electric
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
