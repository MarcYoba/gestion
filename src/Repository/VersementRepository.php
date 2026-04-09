<?php

namespace App\Repository;

use App\Entity\Versement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Versement>
 *
 * @method Versement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Versement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Versement[]    findAll()
 * @method Versement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Versement::class);
    }

//    /**
//     * @return Versement[] Returns an array of Versement objects
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

//    public function findOneBySomeField($value): ?Versement
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findBysommeversementAgence($agence) : array 
    {
        return $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant),0), COALESCE(SUM(v.Om),0), COALESCE(SUM(v.banque),0)')
            ->Where('YEAR(v.createdAd) =:val')
            ->setParameter('val', date('Y'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBysommeDay($date) : array 
    {
        return $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant),0), COALESCE(SUM(v.Om),0), COALESCE(SUM(v.banque),0)')
            ->Where('v.createdAd =:val')
            ->setParameter('val', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBysommeSomme($date_debut,$date_fin,$agence) : array 
    {
        return $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant),0), COALESCE(SUM(v.Om),0), COALESCE(SUM(v.banque),0)')
            ->Where('v.createdAd  BETWEEN :startDate AND :endDate')
            ->setParameter('startDate',$date_debut)
            ->setParameter('endDate',$date_fin)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByVersementSemaine($date_debut,$date_fin,$agence) : array 
    {
        return $this->createQueryBuilder('v')
            ->Where('v.createdAd  BETWEEN :startDate AND :endDate')
            ->setParameter('startDate',$date_debut)
            ->setParameter('endDate',$date_fin)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByMonthAgence($mois, $anne, $agence): array
    {
       return $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant),0) AS Montant, 
            CONCAT(\',\',v.description) AS Description, 
            COALESCE(SUM(v.Om),0) AS Om, COALESCE(SUM(v.banque),0) AS banque')
            ->Where('MONTH(v.createdAd) = :val')
            ->andWhere('YEAR(v.createdAd) = :anne')
            ->andWhere('v.agence = :agence')
            ->setParameter('val', $mois)
            ->setParameter('anne', $anne)
            ->setParameter('agence', $agence)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByVersementTrimestre($trimestre,$annee,$agence) : float 
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
            ->select('COALESCE(SUM(v.montant),0) AS Montant')
            ->Where('v.createdAd BETWEEN :debut AND :fin')
            ->setParameter('debut', $debutTrimestre)
            ->setParameter('fin', $finTrimestre)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0;
    }

    public function findByVersementSemestre($trimestre,$annee,$agence) : float 
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
        $result = $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant),0) AS Montant')
            ->Where('v.createdAd BETWEEN :debut AND :fin')
            ->setParameter('debut', $debutTrimestre)
            ->setParameter('fin', $finTrimestre)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0;
    }
}

