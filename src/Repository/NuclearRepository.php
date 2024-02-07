<?php

namespace App\Repository;

use App\Entity\Nuclear;
use App\Repository\Trait\EnergyTypeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nuclear>
 *
 * @method Nuclear|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nuclear|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nuclear[]    findAll()
 * @method Nuclear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NuclearRepository extends ServiceEntityRepository
{
    use EnergyTypeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nuclear::class);
    }

    //    /**
    //     * @return Nuclear[] Returns an array of Nuclear objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Nuclear
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
