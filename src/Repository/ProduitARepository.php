<?php

namespace App\Repository;

use App\Entity\ProduitA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProduitA>
 *
 * @method ProduitA|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitA|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitA[]    findAll()
 * @method ProduitA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitA::class);
    }

//    /**
//     * @return ProduitA[] Returns an array of ProduitA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProduitA
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    
    public function findByDateExpiration($moisAlerte) :array
    {

            $dateNow = new \DateTime();
            $dateAlerte = (new \DateTime())->modify("+$moisAlerte months");
    
        return $this->createQueryBuilder('p')
            ->leftJoin('p.lots', 'l')
            ->addSelect('l')
            ->where('p.expiration <= :dateAlerte')
            ->andWhere('p.expiration <> :defaut')
            ->setParameter('dateAlerte', $dateAlerte)
            ->setParameter('defaut',1)
            ->orderBy('p.expiration', 'ASC')
            ->getQuery()
            ->getResult();
            ;
    }
    public function findByDoublon(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.nom,p.quantite, COUNT(p.id) as count')
            ->groupBy('p.nom')
            ->having('COUNT(p.id) > 1')
            ->getQuery()
            ->getResult()
        ;

    }
}
