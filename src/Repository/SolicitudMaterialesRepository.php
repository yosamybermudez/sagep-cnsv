<?php

namespace App\Repository;

use App\Entity\SolicitudMateriales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SolicitudMateriales|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudMateriales|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudMateriales[]    findAll()
 * @method SolicitudMateriales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudMaterialesRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, SolicitudMateriales::class);
        $this->entityManager = $entityManager;
    }
    public function delete(Encuentro $encuentro){
        try{
            $this->entityManager->remove($encuentro);
            $this->entityManager->flush();
        } catch (\Exception $e){
            return false;
        }
        return true;
    }

    // /**
    //  * @return SolicitudMateriales[] Returns an array of SolicitudMateriales objects
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
    public function findOneBySomeField($value): ?SolicitudMateriales
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
