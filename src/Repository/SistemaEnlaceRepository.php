<?php

namespace App\Repository;

use App\Entity\SistemaEnlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SistemaEnlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaEnlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaEnlace[]    findAll()
 * @method SistemaEnlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaEnlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SistemaEnlace::class);
    }

    // /**
    //  * @return SistemaEnlace[] Returns an array of SistemaEnlace objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SistemaEnlace
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
