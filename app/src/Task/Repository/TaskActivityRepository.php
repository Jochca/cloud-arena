<?php

declare(strict_types=1);

namespace App\Task\Repository;

use App\Player\Entity\Player;
use App\Task\Entity\Task;
use App\Task\Entity\TaskActivity;
use App\Task\ValueObject\ActivityStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskActivity::class);
    }

    public function findActiveActivityForTaskAndPlayer(Task $task, Player $player): ?TaskActivity
    {
        return $this->createQueryBuilder('ta')
            ->where('ta.task = :task')
            ->andWhere('ta.player = :player')
            ->andWhere('ta.status IN (:statuses)')
            ->setParameter('task', $task)
            ->setParameter('player', $player)
            ->setParameter('statuses', [ActivityStatus::InProgress->value, ActivityStatus::Finished->value])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
