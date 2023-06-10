<?php

namespace App\Repository;

use App\Entity\Encuentro;
use App\Entity\EncuentroAcuerdo;
use App\Entity\EncuentroTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EncuentroAcuerdo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncuentroAcuerdo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncuentroAcuerdo[]    findAll()
 * @method EncuentroAcuerdo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncuentroAcuerdoRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, EncuentroAcuerdo::class);
        $this->entityManager = $entityManager;
    }

    public function findAcuerdosByTipoEncuentro(EncuentroTipo $encuentroTipo){
        //$mayor=$entityManager->createQuery("SELECT a FROM App:EncuentroAcuerdo a left join App:EncuentroDtt e with e.id=a.encuentroDtt where e.tipoEncuentro='".$tipoEncuentro."' ORDER BY  a.fCreate DESC")->setMaxResults(1)->getResult();
        return $this->createQueryBuilder('ea')
            ->leftJoin('ea.encuentro', 'e')
            ->leftJoin('e.tipoEncuentro', 'te')
            ->where('te.id = :val')
            ->setParameter('val', $encuentroTipo->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAcuerdosARevisar(Encuentro $encuentro){
        return $this->createQueryBuilder('ea')
            ->leftJoin('ea.encuentro', 'e')
            ->leftJoin('ea.encuentroAcuerdoTrazabilidads', 'eat')
            ->leftJoin('e.tipoEncuentro', 'te')
//            ->where('te.id = :val and eat.fechaRevision <= :fecha')
            ->where('eat.fechaRevision <= :fecha')
//            ->setParameter('val', $encuentro->getTipoEncuentro()->getId())
            ->andWhere('eat.activo = true')
            ->setParameter('fecha', $encuentro->getFechaEvento())
            ->getQuery()
            ->getResult()
            ;
    }

    public function updateAcuerdosAtrasados(){
        return $this->createQueryBuilder('ea')
            ->leftJoin('ea.encuentroAcuerdoTrazabilidads', 'eat')
            ->where('eat.fechaRevision <= :fecha')
            ->andWhere('eat.activo = true')
            ->andWhere('eat.estado in (:estados)')
            ->setParameter('fecha', new \DateTime())
            ->setParameter('estados', array('En tiempo', 'Pospuesto'))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAcuerdosRevisados(Encuentro $encuentro){
        return $this->createQueryBuilder('ea')
            ->leftJoin('ea.encuentro', 'e')
            ->leftJoin('ea.encuentroAcuerdoTrazabilidads', 'eat')
            ->leftJoin('e.tipoEncuentro', 'te')
            ->where('eat.fechaRevision <= :fecha')
            ->andWhere('eat.activo = false and eat.encuentroModificador = :encuentro')
            ->setParameter('fecha', $encuentro->getFechaEvento())
            ->setParameter('encuentro', $encuentro)
            ->getQuery()
            ->getResult()
            ;
    }

        public function findAcuerdosNuevos(Encuentro $encuentro){
        return $this->createQueryBuilder('ea')
            ->leftJoin('ea.encuentro', 'e')
            ->leftJoin('ea.encuentroAcuerdoTrazabilidads', 'eat')
            ->leftJoin('e.tipoEncuentro', 'te')
//            ->where('te.id = :val and eat.fechaRevision <= :fecha')
//            ->setParameter('val', $encuentro->getTipoEncuentro()->getId())
            ->where('ea.encuentro = :encuentro')
           // ->setParameter('fecha', $encuentro->getFechaEvento())
            ->setParameter('encuentro', $encuentro)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByDates($inicio, $fin)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('e.encuentroAcuerdoTrazabilidads', 'eat')
            ->orderBy('eat.fechaRevision', 'ASC');
        if(isset($inicio) and $inicio !== null){
            $inicio->setTime(0,0,0);
            $qb->andWhere('eat.fechaRevision > :inicio')
                ->setParameter('inicio', $inicio);
        }
        if(isset($fin) and $fin !== null){
            $fin->setTime(23,59,59);
            $qb->andWhere('eat.fechaRevision < :fin')
                ->setParameter('fin', $fin);
        }
        return $qb->getQuery()->getResult();
    }

    public function findByDatesFiltro($inicio, $fin, $filtro)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.encuentroAcuerdoTrazabilidads', 'eat')
            ->where('eat.activo = false');

        if(isset($inicio) and $inicio !== null){
            $inicio->setTime(0,0,0);
            if($filtro == "Fecha de registro"){
                $qb->andWhere('e.fechaInicio > :inicio')
                    ->setParameter('inicio', $inicio);
            } else {
                $qb->andWhere('eat.fechaRevision > :inicio')
                    ->setParameter('inicio', $inicio);
            }

        }
        if(isset($fin) and $fin !== null){
            $fin->setTime(23,59,59);
            if($filtro == "Fecha de registro"){
                $qb->andWhere('e.fechaInicio < :fin')
                    ->setParameter('fin', $fin);
            } else {
                $qb->andWhere('eat.fechaRevision < :fin')
                    ->setParameter('fin', $fin);
            }
        }

        $qb->orderBy('eat.fechaRevision', 'ASC');

        return $qb->getQuery()->getResult();
    }

//    public function refreshAcuerdos(){
//        $querybuilder = $this->createQueryBuilder('a')
//            ->update('App\\Entity\\EncuentroAcuerdo','a')
//            ->set('a.estado', ':estado')
//            ->setParameter('estado' , 'Samy')
//            ->getQuery();
//        $querybuilder->execute();
//    }

    public function delete(EncuentroAcuerdo $encuentroAcuerdo){
        try{
            $this->entityManager->remove($encuentroAcuerdo);
            $this->entityManager->flush();
        } catch (\Exception $e){
            return false;
        }
        return true;
    }

    // /**
    //  * @return EncuentroAcuerdo[] Returns an array of EncuentroAcuerdo objects
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
    public function findOneBySomeField($value): ?EncuentroAcuerdo
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
