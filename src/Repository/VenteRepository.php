<?php

namespace App\Repository;

use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vente>
 *
 * @method Vente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vente[]    findAll()
 * @method Vente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vente::class);
    }

//    /**
//     * @return Vente[] Returns an array of Vente objects
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

//    public function findOneBySomeField($value): ?Vente
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByName($name)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.produit = :name')
            ->setParameter('name', $name)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    // public function findTotalPriceByYear($year)
    // {
    //     return $this->createQueryBuilder('v')
    //         ->select('SUM(v.prix) as totalPrice')
    //         ->andWhere('(v.createdAt) = :year')
    //         ->setParameter('year', $year)
    //         ->getQuery()
    //         ->getSingleScalarResult();
    // }

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
            ->where('v.createdAt BETWEEN :debut AND :fin')
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
            ->where('v.createdAt BETWEEN :debut AND :fin')
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
            ->where('MONTH(v.createdAt) = :moi')
            ->andWhere('v.agence = :agences')
            ->andWhere('YEAR(v.createdAt) = :anne')
            ->setParameter('moi',$moi)
            ->setParameter('agences',$agence)
            ->setParameter('anne',$annee)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $result > 0 ? (int)$result : 0; 
    }

    public function findVentesByWeekWithDaysPrix($date): float
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        $query =  $this->createQueryBuilder('v')
            ->select('SUM(v.prix)')
            ->where('v.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
        ;

        $result = $query->getSingleScalarResult();
    
        return (float) $result; // Retourne un float
    }

    public function findByDay($date) : array 
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);

       return $this->createQueryBuilder('v')
            ->where('v.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findVentesByWeekWithDaysQuantite($date): float
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        $query =  $this->createQueryBuilder('v')
            ->select('SUM(v.quantite)')
            ->where('v.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
        ;

        $result = $query->getSingleScalarResult();
    
        return (float) $result; // Retourne un float
    }

    public function findVentesByWeek($date) : float 
    {
        $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
        $query = $this->createQueryBuilder('v')
            ->select('SUM(v.prix)')
            ->where('v.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
        ;
        $result = $query->getSingleScalarResult();

        return (float) $result;
    }

    public function findVenteInventaire($date) : array 
    {
        return $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.prix),0),COUNT(v.client)')
            ->where('MONTH(v.createdAt) = :val')
            ->setParameter('val', $date)
            //->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByMontantByClientByMonth($date) : array 
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.client', 'c')
            
            // Sélectionne la somme du prix (avec COALESCE pour garantir 0 si aucune vente) et l'objet client
            ->select('COALESCE(SUM(v.prix), 0) AS totalVentes')
            
            // Filtre sur le mois de la date de création de la Vente
            ->where('MONTH(v.createdAt) = :month_val')
            
            // Regroupe par client pour que SUM() fonctionne correctement
            ->groupBy('c')
            
            // Définit le paramètre du mois
            ->setParameter('month_val', $date)
            
            ->getQuery()
            ->getResult()
        ;
    }
}
