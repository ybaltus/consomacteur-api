<?php

namespace App\Repository;

use App\Entity\Thermic;
use App\Repository\Trait\EnergyTypeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Thermic>
 *
 * @method Thermic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thermic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thermic[]    findAll()
 * @method Thermic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThermicRepository extends ServiceEntityRepository
{
    use EnergyTypeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thermic::class);
    }

    //    /**
    //     * @return Thermic[] Returns an array of Thermic objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Thermic
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
