<?php

namespace App\Repository;

use App\Entity\ImmobilisationA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImmobilisationA>
 *
 * @method ImmobilisationA|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImmobilisationA|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImmobilisationA[]    findAll()
 * @method ImmobilisationA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImmobilisationARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImmobilisationA::class);
    }

//    /**
//     * @return ImmobilisationA[] Returns an array of ImmobilisationA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImmobilisationA
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
