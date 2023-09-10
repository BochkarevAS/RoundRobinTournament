<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function add(Team $team, bool $flush = false): void
    {
        $this->getEntityManager()->persist($team);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Team $team, bool $flush = false): void
    {
        $this->getEntityManager()->remove($team);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}