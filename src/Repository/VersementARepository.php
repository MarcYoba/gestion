<?php

namespace App\Repository;

use App\Entity\VersementA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VersementA>
 *
 * @method VersementA|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersementA|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersementA[]    findAll()
 * @method VersementA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersementARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersementA::class);
    }

//    /**
//     * @return VersementA[] Returns an array of VersementA objects
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

//    public function findOneBySomeField($value): ?VersementA
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByDay($date): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.createdAt = :val')
            ->setParameter('val', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeVersement($date,$agence) : float 
    {
        $result = $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant),0) AS Montant')
            ->Where('v.createdAt = :val')
            // ->andWhere('d.agence = :agences')
            ->setParameter('val', $date)
            // ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0;
    }

    public function findBySommeVersementSemaine($first_date,$end_date,$agence) : float 
    {
        $result = $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant),0) AS Montant')
            ->Where('v.createdAt BETWEEN :debut AND :fin')
            // ->andWhere('d.agence = :agences')
            ->setParameter('debut', $first_date)
            ->setParameter('fin', $end_date)
            // ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0;
    }

    public function findByMoi($value,$annee): array
    {
        return $this->createQueryBuilder('v')
            ->select('COALESCE(SUM(v.montant + v.om +v.banque),0) AS Montant, CONCAT(\',\',v.description) AS Description')
            ->Where('MONTH(v.createdAt) = :val')
            ->andWhere('YEAR(v.createdAt) = :anne')
            ->setParameter('val', $value)
            ->setParameter('anne', $annee)
            ->getQuery()
            ->getResult()
        ;
    }
}
