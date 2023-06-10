<?php

namespace App\Repository;

use App\Entity\Trabajador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trabajador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trabajador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trabajador[]    findAll()
 * @method Trabajador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrabajadorRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Trabajador::class);
        $this->entityManager = $entityManager;
    }

    public function delete(Trabajador $trabajador){
        if(count($trabajador->getEncuentros()) === 0) {
            try {
                $this->entityManager->remove($trabajador);
                $this->entityManager->flush();

            } catch (\Exception $e) {
                throw new \Exception();
            }
            return true;
        } else {
            return false;
        }
    }

    public function darBaja(Trabajador $trabajador){
            $trabajador->setAlta(false);
            $this->entityManager->persist($trabajador);
            $this->entityManager->flush();

            return true;
    }

    public function findByNombreCompleto(string $nombreCompleto){
        return $this->createQueryBuilder('t')
          //  ->where(':nombreCompleto LIKE t.nombres and :nombreCompleto LIKE t.apellidos')
            ->where(":nombreCompleto LIKE CONCAT('%', t.nombres, '%')")
            ->andWhere(":nombreCompleto LIKE CONCAT('%', t.apellidos, '%')")
            ->setParameter('nombreCompleto', $nombreCompleto)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Trabajador[] Returns an array of Trabajador objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trabajador
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
