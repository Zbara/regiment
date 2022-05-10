<?php

namespace App\Repository;

use App\Entity\UsersToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsersToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersToken[]    findAll()
 * @method UsersToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersToken::class);
    }

    // /**
    //  * @return UsersToken[] Returns an array of UsersToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsersToken
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
