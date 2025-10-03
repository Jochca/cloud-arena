<?php

declare(strict_types=1);

namespace App\Task\Repository;

use App\Player\Entity\Player;
use App\Session\Entity\Session;
use App\Task\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByPlayer(Player $player): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.player = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->getResult();
    }

    public function findBySession(Session $session): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.session = :session')
            ->setParameter('session', $session)
            ->getQuery()
            ->getResult();
    }

    public function findFreeTasksBySession(Session $session): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.session = :session')
            ->andWhere('t.player IS NULL')
            ->setParameter('session', $session)
            ->getQuery()
            ->getResult();
    }
}
