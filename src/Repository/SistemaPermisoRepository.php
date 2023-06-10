<?php

namespace App\Repository;

use App\Entity\SistemaPermiso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SistemaPermiso|null find($id, $lockMode = null, $lockVersion = null)
 * @method SistemaPermiso|null findOneBy(array $criteria, array $orderBy = null)
 * @method SistemaPermiso[]    findAll()
 * @method SistemaPermiso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SistemaPermisoRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, SistemaPermiso::class);
        $this->entityManager = $entityManager;
    }

    public function cambiarPermiso(SistemaPermiso $permiso, $funcionalidad){
        if ($permiso->{'getPermiso' . ucfirst($funcionalidad)}())
            $permiso->{'setPermiso' . ucfirst($funcionalidad)}(false);
        else
            $permiso->{'setPermiso' . ucfirst($funcionalidad)}(true);

        $permisoAgregar = $permiso->getPermisoAgregar();
        $permisoLeer = $permiso->getPermisoLeer();
        $permisoModificar = $permiso->getPermisoModificar();
        $permisoEliminar = $permiso->getPermisoEliminar();

        if(!$permisoAgregar && !$permisoLeer && !$permisoModificar && !$permisoEliminar) {
            $this->entityManager->remove($permiso);
        }
        else {
            $this->entityManager->persist($permiso);
        }
        $this->entityManager->flush();
        return $permiso;
    }

    // /**
    //  * @return SistemaPermiso[] Returns an array of SistemaPermiso objects
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
    public function findOneBySomeField($value): ?SistemaPermiso
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
