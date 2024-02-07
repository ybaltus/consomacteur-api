<?php

namespace App\Repository;

use App\Entity\Eolien;
use App\Repository\Trait\EnergyTypeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eolien>
 *
 * @method Eolien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eolien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eolien[]    findAll()
 * @method Eolien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EolienRepository extends ServiceEntityRepository
{
    use EnergyTypeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eolien::class);
    }

    //    /**
    //     * @return Eolien[] Returns an array of Eolien objects
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

    //    public function findOneBySomeField($value): ?Eolien
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
