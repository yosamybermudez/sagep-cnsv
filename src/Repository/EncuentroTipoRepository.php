<?php

namespace App\Repository;

use App\Entity\EncuentroTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EncuentroTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncuentroTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncuentroTipo[]    findAll()
 * @method EncuentroTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncuentroTipoRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, EncuentroTipo::class);
        $this->entityManager = $entityManager;
    }

    public function delete(EncuentroTipo $tipo){
        try {
            $this->entityManager->remove($tipo);
            $this->entityManager->flush();
        } catch (\Exception $e){
            throw $e;
        }
        return true;
    }
    // /**
    //  * @return EncuentroTipo[] Returns an array of EncuentroTipo objects
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
    public function findOneBySomeField($value): ?EncuentroTipo
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
