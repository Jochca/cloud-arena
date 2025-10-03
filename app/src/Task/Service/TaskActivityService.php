<?php

declare(strict_types=1);

namespace App\Task\Service;

use App\Player\Entity\Player;
use App\Task\Entity\Task;
use App\Task\Entity\TaskActivity;
use App\Task\Repository\TaskActivityRepository;
use App\Task\ValueObject\ActivityStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class TaskActivityService
{
    public function __construct(
        private TaskActivityRepository $taskActivityRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function createTaskActivity(Task $task, Player $player): ?TaskActivity
    {
        $existingActivity = $this->taskActivityRepository->findActiveActivityForTaskAndPlayer($task, $player);

        if ($existingActivity) {
            return null;
        }

        $taskActivity = new TaskActivity();
        $taskActivity->id = Uuid::v4();
        $taskActivity->task = $task;
        $taskActivity->player = $player;
        $taskActivity->dateStart = new \DateTimeImmutable();
        $taskActivity->dateEnd = new \DateTimeImmutable('+24 hours');
        $taskActivity->status = ActivityStatus::InProgress;

        $this->entityManager->persist($taskActivity);
        $this->entityManager->flush();

        return $taskActivity;
    }

    public function finishTaskActivity(Task $task, Player $player): ?TaskActivity
    {
        $activity = $this->taskActivityRepository->findActiveActivityForTaskAndPlayer($task, $player);

        if (!$activity || ActivityStatus::InProgress !== $activity->status) {
            return null;
        }

        $activity->status = ActivityStatus::Finished;
        $activity->dateEnd = new \DateTimeImmutable();

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return $activity;
    }

    public function cancelTaskActivity(Task $task, Player $player): ?TaskActivity
    {
        $activity = $this->taskActivityRepository->findActiveActivityForTaskAndPlayer($task, $player);

        if (!$activity || ActivityStatus::InProgress !== $activity->status) {
            return null;
        }

        $activity->status = ActivityStatus::Expired;
        $activity->dateEnd = new \DateTimeImmutable();

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        return $activity;
    }
}
