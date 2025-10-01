<?php

declare(strict_types=1);

namespace App\Task\Service;

use App\Task\Repository\TaskActivityRepositoryInterface;
use App\Task\ValueObject\ActivityStatus;
use App\Task\ValueObject\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;

class ActivityExpirationService implements ActivityExpirationServiceInterface
{
    public function __construct(
        private readonly TaskActivityRepositoryInterface $taskActivityRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function expireActivities(): int
    {
        $expiredActivities = $this->taskActivityRepository->findExpiredActivities();
        $expiredCount = 0;

        foreach ($expiredActivities as $activity) {
            // Change activity status to expired (canceled)
            $activity->status = ActivityStatus::Expired;

            // Change related task status to pending
            $activity->task->status = TaskStatus::Pending;

            $expiredCount++;
        }

        if ($expiredCount > 0) {
            $this->entityManager->flush();
        }

        return $expiredCount;
    }
}
