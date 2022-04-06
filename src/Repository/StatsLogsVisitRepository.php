<?php

namespace App\Repository;

use App\Entity\StatsLogsVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatsLogsVisit|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatsLogsVisit|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatsLogsVisit[]    findAll()
 * @method StatsLogsVisit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsLogsVisitRepository extends ServiceEntityRepository
{

    const PAGE_VK = '/friends/get/social';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatsLogsVisit::class);
    }

    public function getCount(string $type = 'all')
    {
        try {
            $builder = $this->createQueryBuilder('a');

            switch ($type) {
                case 'all':
                    $builder
                        ->select('count(a.id)');
                    break;

                case 'day':
                    $builder
                        ->select('count(a.id)')
                        ->andWhere('a.time > :time')
                        ->setParameter('time', time() - 86400);
                    break;
            }
            return $builder->andWhere('a.page = :page')
                ->setParameter('page', self::PAGE_VK)
                ->getQuery()
                ->getSingleScalarResult();

        } catch (NoResultException|NonUniqueResultException $e) {
            return $e->getCode();
        }
    }
}
