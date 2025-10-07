<?php

namespace App\Repository;

use App\Entity\Achat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Achat>
 *
 * @method Achat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achat[]    findAll()
 * @method Achat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achat::class);
    }

//    /**
//     * @return Achat[] Returns an array of Achat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Achat
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findBySommeAchatProduit($date,$produit,$agence) : int
    {
        $result = $this->createQueryBuilder('a')
            ->select('COALESCE(SUM(a.quantite), 0)')
            ->where('YEAR(a.createdAt) = :date')
            ->andWhere('a.produit = :val')
            ->andWhere('a.agence = :agences')
            ->setParameter('date',$date)
            ->setParameter('val', $produit)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    
        return $result > 0 ? (int)$result : 0;
    }

    public function findByMontantMonth($anne,$moi,$agence) : int 
    {
       $result = $this->createQueryBuilder('a')
            ->select('COALESCE(SUM(a.montant), 0)')
            ->where('YEAR(a.createdAt) = :anne')
            ->andWhere('a.agence = :agences')
            ->andWhere('MONTH(a.createdAt) = :moi')
            ->setParameter('anne',$anne)
            ->setParameter('agences',$agence)
            ->setParameter('moi',$moi)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        
        return $result > 0 ? (int)$result : 0;
    }

    public function findByMontantTrimestre($trimestre,$annee,$agence) : int 
    {
        // Déterminer les dates de début et fin du trimestre
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
        $result = $this->createQueryBuilder('a')
            ->select('COALESCE(SUM(a.montant), 0)')
            ->where('a.createdAt BETWEEN :debut AND :fin')
            ->andWhere('a.agence = :agence')
            ->setParameter('debut', $debutTrimestre)
            ->setParameter('fin', $finTrimestre)
            ->setParameter('agence', $agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        
        return $result > 0 ? (int)$result : 0;  
    }

    public function findByMontantSemestre($semestre,$annee,$agence) : int 
    {
        // Déterminer les dates de début et fin du trimestre
        $debutTrimestre = null;
        $finTrimestre = null;
        
        switch($semestre) {
            case 1:
                $debutTrimestre = new \DateTimeImmutable("$annee-01-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-06-30 23:59:59");
                break;
            case 2:
                $debutTrimestre = new \DateTimeImmutable("$annee-07-01 00:00:00");
                $finTrimestre = new \DateTimeImmutable("$annee-12-31 23:59:59");
                break;
            default:
                throw new \InvalidArgumentException("Trimestre invalide : doit être entre 1 et 4");
        }
        $result = $this->createQueryBuilder('a')
            ->select('COALESCE(SUM(a.montant), 0)')
            ->where('a.createdAt BETWEEN :debut AND :fin')
            ->andWhere('a.agence = :agence')
            ->setParameter('debut', $debutTrimestre)
            ->setParameter('fin', $finTrimestre)
            ->setParameter('agence', $agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        
        return $result > 0 ? (int)$result : 0;  
    }
}
