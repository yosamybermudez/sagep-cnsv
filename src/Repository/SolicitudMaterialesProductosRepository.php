<?php

namespace App\Repository;

use App\Entity\SolicitudMateriales;
use App\Entity\SolicitudMaterialesProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SolicitudMaterialesProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudMaterialesProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudMaterialesProductos[]    findAll()
 * @method SolicitudMaterialesProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudMaterialesProductosRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, SolicitudMaterialesProductos::class);
        $this->entityManager = $entityManager;
    }

    public function blankSolicitudesMaterialesProducto(SolicitudMateriales $solicitudMateriales) : void{
        if($solicitudMateriales->getSolicitudesMaterialesProductos() !== null){
            foreach ($solicitudMateriales->getSolicitudesMaterialesProductos() as $producto){
                $this->entityManager->remove($producto);
            }
            $this->entityManager->flush();
        }
    }

    // /**
    //  * @return SolicitudMaterialesProductos[] Returns an array of SolicitudMaterialesProductos objects
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
    public function findOneBySomeField($value): ?SolicitudMaterialesProductos
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
