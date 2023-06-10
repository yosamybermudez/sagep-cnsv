<?php

namespace App\Repository;

use App\Entity\SistemaEntidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SistemaEntidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaEntidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaEntidad[]    findAll()
 * @method SistemaEntidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaEntidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SistemaEntidad::class);
    }

    // /**
    //  * @return SistemaEntidad[] Returns an array of SistemaEntidad objects
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
    public function findOneBySomeField($value): ?SistemaEntidad
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
