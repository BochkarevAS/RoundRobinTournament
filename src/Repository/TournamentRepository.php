<?php

namespace App\Repository;

use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    public function add(Tournament $tournament, bool $flush = false): void
    {
        $this->getEntityManager()->persist($tournament);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tournament $tournament, bool $flush = false): void
    {
        $this->getEntityManager()->remove($tournament);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}