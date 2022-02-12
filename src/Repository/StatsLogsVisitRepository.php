<?php

namespace App\Repository;

use App\Entity\StatsLogsVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatsLogsVisit|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatsLogsVisit|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatsLogsVisit[]    findAll()
 * @method StatsLogsVisit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsLogsVisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatsLogsVisit::class);
    }

    // /**
    //  * @return StatsLogsVisit[] Returns an array of StatsLogsVisit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatsLogsVisit
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
