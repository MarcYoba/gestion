<?php

namespace App\Repository;

use App\Entity\DepenseA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepenseA>
 *
 * @method DepenseA|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepenseA|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepenseA[]    findAll()
 * @method DepenseA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepenseA::class);
    }

//    /**
//     * @return DepenseA[] Returns an array of DepenseA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DepenseA
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByMoi($value,$annee): array
    {
        return $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0) AS Montant, CONCAT(\',\',d.type) AS Type, CONCAT(\',\',d.description) AS Description')
            ->Where('MONTH(d.createdAt) = :val')
            ->andWhere('YEAR(d.createdAt) = :anne')
            ->setParameter('val', $value)
            ->setParameter('anne', $annee)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDay($date) : array 
    {
        return $this->createQueryBuilder('d')
            ->where('d.createdAt = :val')
            ->setParameter('val',$date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeDepense($date,$agence) : float 
    {
        $result = $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0) AS Montant')
            ->Where('d.createdAt = :val')
            // ->andWhere('d.agence = :agences')
            ->setParameter('val', $date)
            // ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0;
    }

    public function findBySommeDepenseSemain($first_date,$end_date,$agence) : float 
    {
        $result = $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0) AS Montant')
            ->Where('d.createdAt BETWEEN :debut AND :fin')
            // ->andWhere('d.agence = :agences')
            ->setParameter('debut', $first_date)
            ->setParameter('fin', $end_date)
            // ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0;
    }

    public function findByDepenseSemain($first_date,$end_date,$agence) : array
    {
        return $this->createQueryBuilder('d')
            ->Where('d.createdAt BETWEEN :debut AND :fin')
            // ->andWhere('d.agence = :agences')
            ->setParameter('debut', $first_date)
            ->setParameter('fin', $end_date)
            // ->setParameter('agences',$agence)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDepensesTrimestre($trimestre,$annee,$id) : int 
    {
        $debutTrimestre = null;
        $finTrimestre = null;
        
        switch($trimestre) {
            case 1:
                $debutTrimestre = new \DateTimeImmutable("$annee-01-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-03-31 23:59:59");
                break;
            case 2:
                $debutTrimestre = new \DateTimeImmutable("$annee-04-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-06-30 23:59:59");
                break;
            case 3:
                $debutTrimestre = new \DateTimeImmutable("$annee-07-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-09-30 23:59:59");
                break;
            case 4:
                $debutTrimestre = new \DateTimeImmutable("$annee-10-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-12-31 23:59:59");
                break;
            default:
                throw new \InvalidArgumentException("Trimestre invalide : doit être entre 1 et 4");
        }
        $result = $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0) AS Montant')
            ->Where('d.createdAt BETWEEN :debut AND :fin')
            // ->andWhere('d.agence = :agences')
            ->setParameter('debut', $debutTrimestre)
            ->setParameter('fin', $finTrimestre)
            // ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0; 
    }

    public function findByDepensesSemestre($trimestre,$annee,$id) : int 
    {
        $debutTrimestre = null;
        $finTrimestre = null;
        
        switch($trimestre) {
            case 1:
                $debutTrimestre = new \DateTimeImmutable("$annee-01-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-06-30 23:59:59");
                break;
            case 2:
                $debutTrimestre = new \DateTimeImmutable("$annee-07-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-12-31 23:59:59");
                break;
            default:
                throw new \InvalidArgumentException("Trimestre invalide : doit être entre 1 et 2");
        }
        $result = $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0) AS Montant')
            ->Where('d.createdAt BETWEEN :debut AND :fin')
            // ->andWhere('d.agence = :agences')
            ->setParameter('debut', $debutTrimestre)
            ->setParameter('fin', $finTrimestre)
            // ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0; 
    }
}
