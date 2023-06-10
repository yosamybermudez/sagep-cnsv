<?php

namespace App\Repository;

use App\Entity\EncuentroAcuerdoPeriodico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EncuentroAcuerdoPeriodico|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncuentroAcuerdoPeriodico|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncuentroAcuerdoPeriodico[]    findAll()
 * @method EncuentroAcuerdoPeriodico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncuentroAcuerdoPeriodicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EncuentroAcuerdoPeriodico::class);
    }

    // /**
    //  * @return EncuentroAcuerdoPeriodico[] Returns an array of EncuentroAcuerdoPeriodico objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EncuentroAcuerdoPeriodico
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
