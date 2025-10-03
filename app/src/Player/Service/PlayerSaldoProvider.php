<?php

declare(strict_types=1);

namespace App\Player\Service;

use App\Player\Entity\Player;
use App\Task\Repository\TaskActivityRepositoryInterface;
use App\Task\Repository\TaskRepositoryInterface;
use App\Task\ValueObject\ActivityStatus;
use App\Task\ValueObject\TaskType;

class PlayerSaldoProvider implements PlayerSaldoProviderInterface
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly TaskActivityRepositoryInterface $taskActivityRepository,
    ) {
    }

    public function calculateSaldo(Player $player): int
    {
        $saldo = 0; // Exit point is always 0

        $tasks = $this->taskRepository->findByPlayer($player);

        foreach ($tasks as $task) {
            $taskValue = $task->value;

            // Get activities for this specific task and player
            $activities = $this->getActivitiesForTaskAndPlayer($task, $player);

            if (empty($activities)) {
                // No activities for this task
                if (TaskType::Duty === $task->type) {
                    // Duties without activities are considered not completed - penalty
                    $saldo -= $taskValue;
                }
            // Activities without activities don't affect saldo (no reward, no penalty)
            } else {
                // Process each activity for this task
                foreach ($activities as $activity) {
                    if (TaskType::Activity === $task->type) {
                        // For activities: increase by completed (Finished), decrease by canceled (Expired)
                        if (ActivityStatus::Finished === $activity->status) {
                            $saldo += $taskValue;
                        } elseif (ActivityStatus::Expired === $activity->status) {
                            $saldo -= $taskValue;
                        }
                    } elseif (TaskType::Duty === $task->type) {
                        // For duties: decrease by all that were not completed (not Finished)
                        if (ActivityStatus::Finished !== $activity->status) {
                            $saldo -= $taskValue;
                        }
                        // If Finished, no penalty (no positive reward either)
                    }
                }
            }
        }

        return $saldo;
    }

    private function getActivitiesForTaskAndPlayer($task, Player $player): array
    {
        // Get all activities for this player and filter by task
        $allActivities = $this->taskActivityRepository->findActivitiesForSaldoCalculation($player);

        return array_filter($allActivities, function ($activity) use ($task) {
            return $activity->task->id->equals($task->id);
        });
    }
}
