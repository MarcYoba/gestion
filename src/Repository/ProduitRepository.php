<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
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

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByName($nom): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.prixvente as prix, p.quantite as quantite')
            ->andWhere('p.prix = :val')
            ->setParameter('val', $nom)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function FindByBonCommandAutre() : array {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.bondCommandes', 'b') 
            ->leftJoin('p.fournisseurs', 'f') 
            ->select('p.nom, p.quantite')
            ->where('b.statut > 0')
            ->where('f.id IS NULL')            
            ->groupBy('p.nom')
            ->getQuery()
            ->getResult();
    }
    public function FindByBonCommandFournisseur($fournisseur) : array {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.bondCommandes', 'b')
            ->innerJoin('p.fournisseurs', 'f')
            ->select('p.nom, p.quantite, b.limite')
            ->where('b.statut > 0')
            ->Where('f.id = :fourni')
            ->setParameter('fourni', $fournisseur)
            ->addGroupBy('p.nom')
            ->getQuery()
            ->getResult();
    }
}
