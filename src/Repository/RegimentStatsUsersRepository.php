<?php

namespace App\Repository;

use App\Entity\RegimentStatsUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegimentStatsUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegimentStatsUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegimentStatsUsers[]    findAll()
 * @method RegimentStatsUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegimentStatsUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegimentStatsUsers::class);
    }

    // /**
    //  * @return RegimentStatsUsers[] Returns an array of RegimentStatsUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RegimentStatsUsers
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
