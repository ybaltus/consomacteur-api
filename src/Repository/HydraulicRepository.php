<?php

namespace App\Repository;

use App\Entity\Hydraulic;
use App\Repository\Trait\EnergyTypeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hydraulic>
 *
 * @method Hydraulic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hydraulic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hydraulic[]    findAll()
 * @method Hydraulic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HydraulicRepository extends ServiceEntityRepository
{
    use EnergyTypeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hydraulic::class);
    }

    //    /**
    //     * @return Hydraulic[] Returns an array of Hydraulic objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Hydraulic
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
