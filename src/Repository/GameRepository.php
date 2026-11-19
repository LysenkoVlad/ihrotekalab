<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Game> */
final class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /** @return list<Game> */
    public function catalog(): array
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.category', 'c')->addSelect('c')
            ->orderBy('g.title', 'ASC')
            ->getQuery()->getResult();
    }
}
