<?php

namespace App\Repository;

use App\Entity\RegimentUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegimentUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegimentUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegimentUsers[]    findAll()
 * @method RegimentUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegimentUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegimentUsers::class);
    }

    public function findLatest(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.level', 'DESC');
    }


    // /**
    //  * @return RegimentUsers[] Returns an array of RegimentUsers objects
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
    public function findOneBySomeField($value): ?RegimentUsers
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
