<?php

namespace App\Repository;

use App\Entity\UsersScript;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsersScript|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersScript|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersScript[]    findAll()
 * @method UsersScript[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersScriptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersScript::class);
    }

    public function findLatest(string $category = 'all', array $friends = []): \Doctrine\ORM\QueryBuilder
    {
        $builder = $this->createQueryBuilder('a');
        return $builder->orderBy('a.lastTime', 'DESC');
    }

    public function getLastId()
    {
        $builder = $this->createQueryBuilder('a');

        return $builder->andWhere('a.lastTime > :time')
            ->setParameter('time', time() - 86400)
            ->getQuery()
            ->getResult();
    }
}
