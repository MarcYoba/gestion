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
}

