<?php

declare(strict_types=1);

namespace App\Task\Repository;

use App\Player\Entity\Player;
use App\Task\Entity\Task;
use App\Task\Entity\TaskActivity;
use App\Task\ValueObject\ActivityStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskActivityRepository extends ServiceEntityRepository implements TaskActivityRepositoryInterface
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

    public function findActivitiesForSaldoCalculation(Player $player): array
    {
        return $this->createQueryBuilder('ta')
            ->leftJoin('ta.task', 't')
            ->where('ta.player = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->getResult();
    }

    public function findExpiredActivities(): array
    {
        $now = new \DateTimeImmutable();

        return $this->createQueryBuilder('ta')
            ->where('ta.dateStart < :now')
            ->andWhere('ta.dateEnd < :now')
            ->andWhere('ta.status = :inProgressStatus')
            ->setParameter('now', $now)
            ->setParameter('inProgressStatus', ActivityStatus::InProgress->value)
            ->getQuery()
            ->getResult();
    }
}
