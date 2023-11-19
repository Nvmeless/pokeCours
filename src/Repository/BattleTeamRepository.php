<?php

namespace App\Repository;

use App\Entity\BattleTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BattleTeam>
 *
 * @method BattleTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method BattleTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method BattleTeam[]    findAll()
 * @method BattleTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BattleTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BattleTeam::class);
    }

//    /**
//     * @return BattleTeam[] Returns an array of BattleTeam objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BattleTeam
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
