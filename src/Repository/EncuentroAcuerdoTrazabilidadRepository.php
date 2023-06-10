<?php

namespace App\Repository;

use App\Entity\EncuentroAcuerdoTrazabilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EncuentroAcuerdoTrazabilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncuentroAcuerdoTrazabilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncuentroAcuerdoTrazabilidad[]    findAll()
 * @method EncuentroAcuerdoTrazabilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncuentroAcuerdoTrazabilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EncuentroAcuerdoTrazabilidad::class);
    }

    // /**
    //  * @return EncuentroAcuerdoTrazabilidad[] Returns an array of EncuentroAcuerdoTrazabilidad objects
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
    public function findOneBySomeField($value): ?EncuentroAcuerdoTrazabilidad
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
