<?php

namespace App\Repository;

use App\Entity\ActifA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActifA>
 *
 * @method ActifA|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActifA|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActifA[]    findAll()
 * @method ActifA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActifARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActifA::class);
    }

//    /**
//     * @return ActifA[] Returns an array of ActifA objects
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

//    public function findOneBySomeField($value): ?ActifA
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByRefDate($ref, $date) : ?ActifA
    {
        return $this->createQueryBuilder('a')
            ->where('a.REF = :refference')
            ->andWhere('YEAR(a.created) = :date')
            ->setParameter('refference',$ref)
            ->setParameter('date',$date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByYear($anne) : array 
    {
        return $this->createQueryBuilder('a')
            ->andWhere('YEAR(a.created) = :date')
            ->setParameter('date',$anne)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySomme($anne,$cathegire): array
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.brut),SUM(a.amortissement),SUM(a.net)')
            ->Where('YEAR(a.created) = :date')
            ->andWhere('a.cathegorie = :cathego')
            ->setParameter('date',$anne)
            ->setParameter('cathego',$cathegire)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCreanceAssimiles($anne,$cathegire) : array 
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.brut), SUM(a.amortissement) , SUM(a.net) ')
            ->where('YEAR(a.created) = :date')
            ->andWhere('a.cathegorie = :categorie')
            ->andWhere('a.REF IN (:refs)') // Utilisation de IN au lieu de multiples AND
            ->setParameter('date', $anne)
            ->setParameter('categorie', $cathegire) // Correction du nom de variable
            ->setParameter('refs', ['BH', 'BI', 'BJ']) // Passage d'un tableau BG BB BA
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeCirculan($anne,$cathegire) : array 
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.brut), SUM(a.amortissement) , SUM(a.net) ')
            ->where('YEAR(a.created) = :date')
            ->andWhere('a.cathegorie = :categorie')
            ->andWhere('a.REF IN (:refs)') // Utilisation de IN au lieu de multiples AND
            ->setParameter('date', $anne)
            ->setParameter('categorie', $cathegire) // Correction du nom de variable
            ->setParameter('refs', ['BG', 'BB', 'BA']) // Passage d'un tableau 
            ->getQuery()
            ->getResult()
        ;
    }
}
