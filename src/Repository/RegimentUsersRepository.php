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

    public function findLatest(string $category = 'all', array $friends = []): \Doctrine\ORM\QueryBuilder
    {
        $builder = $this->createQueryBuilder('a');

        switch ($category) {

            case 'yes':
                $builder->andWhere('a.socId  IN (:ids)')
                    ->setParameter('ids', $friends);
                break;

            case 'no':
                $builder->andWhere('a.socId NOT IN (:ids)')
                    ->setParameter('ids', $friends);
                break;
            default:
        }
        return  $builder->orderBy('a.level', 'DESC');
    }

    public function updateTime()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.updateTime', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
