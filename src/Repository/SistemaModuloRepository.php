<?php

namespace App\Repository;

use App\Entity\SistemaModulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SistemaModulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaModulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaModulo[]    findAll()
 * @method SistemaModulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaModuloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SistemaModulo::class);
    }

    // /**
    //  * @return SistemaModulo[] Returns an array of SistemaModulo objects
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
    public function findOneBySomeField($value): ?SistemaModulo
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
