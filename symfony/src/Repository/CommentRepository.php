<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class TrickRepository
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param int $limit
     * @param int $page
     * @param int $trickId
     * @return Comment[]
     */
    public function pagination($limit,$page,$trickId)
    {
        $offset = ($page*$limit) - $limit;

        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.trick = :trick')
            ->setParameter('trick', $trickId)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $qb->execute();
    }
}
