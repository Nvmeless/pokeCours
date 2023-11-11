<?php

namespace App\Repository;

use App\Entity\Itemdex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Itemdex>
 *
 * @method Itemdex|null find($id, $lockMode = null, $lockVersion = null)
 * @method Itemdex|null findOneBy(array $criteria, array $orderBy = null)
 * @method Itemdex[]    findAll()
 * @method Itemdex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemdexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Itemdex::class);
    }

//    /**
//     * @return Itemdex[] Returns an array of Itemdex objects
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

//    public function findOneBySomeField($value): ?Itemdex
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
