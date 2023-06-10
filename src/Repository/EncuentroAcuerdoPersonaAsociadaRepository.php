<?php

namespace App\Repository;

use App\Entity\EncuentroAcuerdo;
use App\Entity\EncuentroAcuerdoPersonaAsociada;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EncuentroAcuerdoPersonaAsociada|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncuentroAcuerdoPersonaAsociada|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncuentroAcuerdoPersonaAsociada[]    findAll()
 * @method EncuentroAcuerdoPersonaAsociada[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncuentroAcuerdoPersonaAsociadaRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, EncuentroAcuerdoPersonaAsociada::class);
        $this->entityManager = $entityManager;
    }

    public function deleteEncuentroAcuerdoPersonasAsociadas(EncuentroAcuerdo $encuentroAcuerdo){
        $result = $this->createQueryBuilder('eapa')
            ->where('eapa.acuerdo = :encuentroAcuerdo')
            ->setParameter('encuentroAcuerdo', $encuentroAcuerdo)
            ->getQuery()
            ->getResult()
            ;

        foreach ($result as $item){
            $this->entityManager->remove($item);
        }

        $this->entityManager->flush();
    }

    // /**
    //  * @return EncuentroAcuerdoPersonaAsociada[] Returns an array of EncuentroAcuerdoPersonaAsociada objects
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
    public function findOneBySomeField($value): ?EncuentroAcuerdoPersonaAsociada
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
