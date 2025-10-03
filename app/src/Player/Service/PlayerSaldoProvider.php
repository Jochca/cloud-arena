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
        $saldo = 0;

        $tasks = $this->taskRepository->findByPlayer($player);

        foreach ($tasks as $task) {
            $taskValue = $task->value;

            $activities = $this->getActivitiesForTaskAndPlayer($task, $player);

            if (empty($activities)) {
                if (TaskType::Duty === $task->type) {
                    $saldo -= $taskValue;
                }
            } else {
                foreach ($activities as $activity) {
                    if (TaskType::Activity === $task->type) {
                        if (ActivityStatus::Finished === $activity->status) {
                            $saldo += $taskValue;
                        } elseif (ActivityStatus::Expired === $activity->status) {
                            $saldo -= $taskValue;
                        }
                    } elseif (TaskType::Duty === $task->type) {
                        if (ActivityStatus::Finished !== $activity->status) {
                            $saldo -= $taskValue;
                        }
                    }
                }
            }
        }

        return $saldo;
    }

    private function getActivitiesForTaskAndPlayer($task, Player $player): array
    {
        $allActivities = $this->taskActivityRepository->findActivitiesForSaldoCalculation($player);

        return array_filter($allActivities, function ($activity) use ($task) {
            return $activity->task->id->equals($task->id);
        });
    }
}
