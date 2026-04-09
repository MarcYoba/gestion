<?php

namespace App\Repository;

use App\Entity\Depenses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Depenses>
 *
 * @method Depenses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depenses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depenses[]    findAll()
 * @method Depenses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepensesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depenses::class);
    }

//    /**
//     * @return Depenses[] Returns an array of Depenses objects
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

//    public function findOneBySomeField($value): ?Depenses
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByDay($date) : array 
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);

        return $this->createQueryBuilder('d')
            ->where('d.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate',$startDate)
            ->setParameter('endDate',$endDate)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeDepenseAgence($agence) : array 
    {
        return $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0)')
            ->where('YEAR(d.createdAt) =:valt')
            ->andWhere('d.agence =:agences')
            ->setParameter('valt',date('Y'))
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeDepenseAgenceAll() : array 
    {
        return $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0)')
            ->where('YEAR(d.createdAt) =:valt')
            ->setParameter('valt',date('Y'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeDay($date) : float 
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);

        $query = $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0)')
            ->where('d.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate',$startDate)
            ->setParameter('endDate',$endDate)
            ->getQuery()
        ;

        $result = $query->getSingleScalarResult();
    
        return (float) $result;
    }

    public function findBySommeDepenseSemaine($start_Date,$end_Date,$agence) : array 
    {
        $startDate = (clone $start_Date)->setTime(0, 0, 0);
        $endDate = (clone $end_Date)->setTime(23, 59, 59);

        return $this->createQueryBuilder('d')
            ->where('d.createdAt BETWEEN :startDate AND :endDate')
            ->andWhere('d.agence =:agences')
            ->setParameter('startDate',$startDate)
            ->setParameter('endDate',$endDate)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeSemaine($start_Date,$end_Date,$agence) : float 
    {
        $startDate = (clone $start_Date)->setTime(0, 0, 0);
        $endDate = (clone $end_Date)->setTime(23, 59, 59);

        $query = $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0)')
            ->where('d.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate',$startDate)
            ->setParameter('endDate',$endDate)
            ->getQuery()
        ;

        $result = $query->getSingleScalarResult();
    
        return (float) $result;
    }

    public function findByMonthAgence($moi,$anne,$agence): array
    {
        return $this->createQueryBuilder('d')
            ->select('COALESCE(SUM(d.montant),0) AS Montant, 
            CONCAT(\',\',d.type) AS Type, 
            CONCAT(\',\',d.description) AS Description')
            ->Where('MONTH(d.createdAt) = :val')
            ->andWhere('YEAR(d.createdAt) = :anne')
            ->andWhere('d.agence = :agences')
            ->setParameter('val', $moi)
            ->setParameter('anne', $anne)
            ->setParameter('agences', $agence)
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
