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

    public function findByDatePeremption($agence) :array 
    {
        return $this->createQueryBuilder('p')
            ->join('p.lots','l')
            ->select('p.nom,p.expiration AS lot1, l.expiration As lot2')
            ->where('p.quantite > 0')
            ->andWhere('p.agence =:agences')
            ->setParameter('agences',$agence)
            ->groupBy('p.nom')
            ->orderBy('p.nom','ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function FindByBonCommandFournisseur($fournisseur) : array {
        return $this->createQueryBuilder('p')
            ->join('p.bondCommandeAs','b')
            ->join('p.achatAs','a')
            ->select('p.nom,p.quantite,b.limite')
            ->where('b.statut > 0')
            ->andWhere('a.forunisseur =:fourni')
            ->setParameter('fourni',$fournisseur)
            ->groupBy('p.nom')
            ->getQuery()
            ->getResult();
    }

    public function FindByBonCommandAutre() : array {
        return $this->createQueryBuilder('p')
            ->join('p.bondCommandeAs', 'b') 
            ->leftJoin('p.achatAs', 'a')  
            ->select('p.nom, p.quantite')
            ->where('b.statut > 0')        
            ->andWhere('a.id IS NULL')     
            ->groupBy('p.nom')
            ->getQuery()
            ->getResult();
    }
}
