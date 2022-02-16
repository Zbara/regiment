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

    public function getLevelRank(){
//select (select count(*) from `regiment_users` r where r.level>=u.level) as rank from `regiment_users` u where u.soc_id=1

        $builder = $this->createQueryBuilder('a');

        $builder->select('a')
            ->addSelect('(select count(*) from `regiment_users` r where r.level>=u.level) as rank'
            )
            ->from('e:Address', 'a')
            ->where('a.addressId = :addressId')
            ->setParameter('addressId', 1);
    }

    public function getLastId(int $userId)
    {
        $builder = $this->createQueryBuilder('a');

        return $builder->andWhere('a.socId  IN (:ids)')
            ->setParameter('ids', $userId)
            ->andWhere('a.updateTime > :time')
            ->setParameter('time', time() - 500)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
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
