<?php

namespace App\Repository;

use App\Entity\Actif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Stmt\Return_;

/**
 * @extends ServiceEntityRepository<Actif>
 *
 * @method Actif|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actif|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actif[]    findAll()
 * @method Actif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actif::class);
    }

//    /**
//     * @return Actif[] Returns an array of Actif objects
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

//    public function findOneBySomeField($value): ?Actif
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByRefDate($ref, $date) : ?Actif 
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
            ->select('SUM(a.brut),SUM(a.amortissement),SUM(a.net)')
            ->Where('YEAR(a.created) = :date')
            ->andWhere('a.cathegorie = :cathego')
            ->andWhere('a.REF = :ref1')
            ->andWhere('a.REF = :ref2')
            ->andWhere('a.REF = :ref3')
            ->setParameter('date',$anne)
            ->setParameter('cathego',$cathegire)
            ->setParameter('ref1','BH')
            ->setParameter('ref2','BI')
            ->setParameter('ref3','BJ')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeCirculan($anne,$cathegire) : array 
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.brut),SUM(a.amortissement),SUM(a.net)')
            ->Where('YEAR(a.created) = :date')
            ->andWhere('a.cathegorie = :cathego')
            ->andWhere('a.REF = :ref1')
            ->andWhere('a.REF = :ref2')
            ->andWhere('a.REF = :ref3')
            ->setParameter('date',$anne)
            ->setParameter('cathego',$cathegire)
            ->setParameter('ref1','BG')
            ->setParameter('ref2','BB')
            ->setParameter('ref3','BA')
            ->getQuery()
            ->getResult()
        ;
    }
}
