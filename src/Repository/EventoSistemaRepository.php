<?php

namespace App\Repository;

use App\Entity\EventoSistema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventoSistema|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventoSistema|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventoSistema[]    findAll()
 * @method EventoSistema[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventoSistemaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventoSistema::class);
    }

    // /**
    //  * @return EventoSistema[] Returns an array of EventoSistema objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventoSistema
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
