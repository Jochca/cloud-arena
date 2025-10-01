<?php

declare(strict_types=1);

namespace App\Task\Repository;

use App\Player\Entity\Player;
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
}
