<?php

namespace App\Repository;

use App\Entity\AlmacenProducto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlmacenProducto|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlmacenProducto|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlmacenProducto[]    findAll()
 * @method AlmacenProducto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlmacenProductoRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, AlmacenProducto::class);
        $this->entityManager = $entityManager;
    }

    public function findProductos(){
        return $this->createQueryBuilder('p')
            ->select('p')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function delete(AlmacenProducto $producto){
        try{
            $this->entityManager->remove($producto);
            $this->entityManager->flush();
        } catch (\Exception $e){
            return false;
        }
        return true;
    }

    // /**
    //  * @return AlmacenProducto[] Returns an array of AlmacenProducto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlmacenProducto
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
