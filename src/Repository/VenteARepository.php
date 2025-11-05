<?php

namespace App\Repository;

use App\Entity\VenteA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Twig\Node\Expression\Binary\SubBinary;

/**
 * @extends ServiceEntityRepository<VenteA>
 *
 * @method VenteA|null find($id, $lockMode = null, $lockVersion = null)
 * @method VenteA|null findOneBy(array $criteria, array $orderBy = null)
 * @method VenteA[]    findAll()
 * @method VenteA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VenteA::class);
    }

//    /**
//     * @return VenteA[] Returns an array of VenteA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VenteA
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findRapportToDay($date) : array 
    {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        
        ;
    }

    public function findVentesByWeekWithDaysPrix($date): float
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        $query =  $this->createQueryBuilder('v')
            ->select('SUM(v.prix)')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
        ;

        $result = $query->getSingleScalarResult();
    
        return (float) $result; // Retourne un float
    }

    public function findVentesByWeekWithDaysQuantite($date): float
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        $query =  $this->createQueryBuilder('v')
            ->select('SUM(v.quantite)')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
        ;

        $result = $query->getSingleScalarResult();
    
        return (float) $result; // Retourne un float
    }
    
    public function findVenteMontantYear($agence,$year) : float 
    {
        $startDate = new \DateTime($year . '-01-01');
        $endDate = new \DateTime($year . '-12-31 23:59:59');

        $query = $this->createQueryBuilder('v')
            ->select('SUM(v.prix)')
            ->where('v.agence = :agence')
            ->andWhere('v.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('agence', $agence)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery();

        $result = $query->getSingleScalarResult();

        return $result !== null ? (float) $result : 0.0;
    }

    public function findVenteMontantLastYear($agence,$year) : float 
    {
        $year = $year -1;
        $startDate = new \DateTime($year . '-01-01');
        $endDate = new \DateTime($year . '-12-31 23:59:59');

        $query = $this->createQueryBuilder('v')
            ->select('SUM(v.prix)')
            ->where('v.agence = :agence')
            ->andWhere('v.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('agence', $agence)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery();

        $result = $query->getSingleScalarResult();

        return $result !== null ? (float) $result : 0.0;
    }

    public function findRapportVenteToWeek($date_debut, $date_fin,$agence) : array 
    {
        $startDate = (clone $date_debut)->setTime(0, 0, 0);
        $endDate = (clone $date_fin)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence =:agences')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getResult()
        
        ;
    }

    public function findRapportVenteToWeekCreditOm($date_debut, $date_fin,$agence) : array 
    {
        $startDate = (clone $date_debut)->setTime(0, 0, 0);
        $endDate = (clone $date_fin)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence =:agences')
            ->andWhere('v.credit >:credits')
            ->orWhere('v.momo >:momos')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->setParameter('credits',0)
            ->setParameter('momos',0)
            ->getQuery()
            ->getResult()
        
        ;
    }

    public function findRapportVenteToWeekOm($date_debut, $date_fin,$agence) : array 
    {
        $startDate = (clone $date_debut)->setTime(0, 0, 0);
        $endDate = (clone $date_fin)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence =:agences')
            ->andWhere('v.momo >:momos')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->setParameter('momos',0)
            ->getQuery()
            ->getResult()
        
        ;
    }

    public function findRapportVenteToWeekCredit($date_debut, $date_fin,$agence) : array 
    {
        $startDate = (clone $date_debut)->setTime(0, 0, 0);
        $endDate = (clone $date_fin)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence =:agences')
            ->andWhere('v.credit >:credit')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->setParameter('credit',0)
            ->getQuery()
            ->getResult()
        
        ;
    }

    public function findRapportVenteToWeekCash($date_debut, $date_fin,$agence) : array 
    {
        $startDate = (clone $date_debut)->setTime(0, 0, 0);
        $endDate = (clone $date_fin)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence =:agences')
            ->andWhere('v.cash >:cash')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->setParameter('cash',0)
            ->getQuery()
            ->getResult()
        
        ;
    }
    
    public function findRapportMensuel($mois,$annee) : array
    {
        // S'assurer que $mois est un entier
        $mois = (int)$mois;
        $annee = (int)$annee;

        // Créer les dates de début et fin du mois
        $startDate = new \DateTime("$annee-$mois-01");
        $endDate = (clone $startDate)->modify('last day of this month');

        return $this->createQueryBuilder('v')
            ->select('SUM(v.prix) AS TotalVente, SUM(v.quantite) AS quantite, 
                    SUM(v.cash) as cash, SUM(v.reduction) as reduction,
                    SUM(v.banque) AS banque, SUM(v.credit) AS credit, SUM(v.momo) AS momo')
            ->where('v.createAt BETWEEN :start AND :end')
            ->setParameter('start', $startDate->format('Y-m-d 00:00:00'))
            ->setParameter('end', $endDate->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult(); 
    }

    public function findByMontantTrimestre($trimestre,$annee,$agence) : int 
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

        $result = $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.prix),0)')
            ->where('v.createAt BETWEEN :debut AND :fin')
            ->andWhere('v.agence = :agences')
            ->setParameter('debut',$debutTrimestre)
            ->setParameter('fin',$finTrimestre)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $result > 0 ? (int)$result : 0; 
    }
    
    public function findByMontantSemestre($trimestre,$annee,$agence) : int 
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
                throw new \InvalidArgumentException("Trimestre invalide : doit être entre 1 et 3");
        }

        $result = $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.prix),0)')
            ->where('v.createAt BETWEEN :debut AND :fin')
            ->andWhere('v.agence = :agences')
            ->setParameter('debut',$debutTrimestre)
            ->setParameter('fin',$finTrimestre)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $result > 0 ? (int)$result : 0; 
    }

    public function findByMontantMonth($moi,$annee,$agence) : int 
    {
        $result = $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.prix),0)')
            ->where('MONTH(v.createAt) = :moi')
            ->andWhere('v.agence = :agences')
            ->andWhere('YEAR(v.createAt) = :anne')
            ->setParameter('moi',$moi)
            ->setParameter('agences',$agence)
            ->setParameter('anne',$annee)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $result > 0 ? (int)$result : 0; 
    }

    public function findVentesByWeek($date) : float 
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
        $query = $this->createQueryBuilder('v')
            ->select('SUM(v.prix)')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
        ;
        $result = $query->getSingleScalarResult();

        return (float) $result;
    }

    public function findRapportToDayCreditOm($date,$agence) : array 
    {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence = :agence')
            ->andWhere('v.credit > :credit')
            ->orWhere('v.momo > :momo')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agence', $agence)
            ->setParameter('credit', 0)
            ->setParameter('momo', 0)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRapportToDayOm($date,$agence) : array 
    {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence = :agence')
            ->andWhere('v.momo > :momo')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agence', $agence)
            ->setParameter('momo', 0)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRapportToDayCredit($date,$agence) : array 
    {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence = :agence')
            ->andWhere('v.credit > :credit')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agence', $agence)
            ->setParameter('credit', 0)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRapportToDayCash($date,$agence) : array 
    {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('v.agence = :agence')
            ->andWhere('v.cash > :cash')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agence', $agence)
            ->setParameter('cash', 0)
            ->getQuery()
            ->getResult()
        ;
    }
}
