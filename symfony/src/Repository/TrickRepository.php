<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class TrickRepository
 */
class TrickRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return Trick[]
     */
    public function pagination($limit,$page)
    {
        $offset = ($page*$limit) - $limit;

        $qb = $this->createQueryBuilder('p')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $qb->execute();
    }

    public function threeLastTrick()
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(3)
            ->getQuery();

        return $qb->execute();
    }
}

