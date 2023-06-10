<?php

namespace App\Repository;

use App\Entity\FileDocumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileDocumento|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileDocumento|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileDocumento[]    findAll()
 * @method FileDocumento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileDocumentoRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, FileDocumento::class);
        $this->entityManager = $entityManager;
    }

    public function delete(FileDocumento $fileDocumento){
        try{
            $this->entityManager->remove($fileDocumento);
            $this->entityManager->flush();

        } catch (\Exception $e){
            return false;
        }
        return true;
    }

    // /**
    //  * @return FileDocumento[] Returns an array of FileDocumento objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FileDocumento
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
