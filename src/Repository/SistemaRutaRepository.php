<?php

namespace App\Repository;

use App\Entity\SistemaRuta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SistemaRuta|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaRuta|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaRuta[]    findAll()
 * @method SistemaRuta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaRutaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SistemaRuta::class);
    }

    // /**
    //  * @return SistemaRuta[] Returns an array of SistemaRuta objects
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
    public function findOneBySomeField($value): ?SistemaRuta
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
