<?php

declare(strict_types=1);

namespace App\Tests\Player\Service;

use App\Player\Entity\Player;
use App\Player\Service\PlayerSaldoProvider;
use App\Task\Entity\Task;
use App\Task\Entity\TaskActivity;
use App\Task\Repository\TaskActivityRepositoryInterface;
use App\Task\Repository\TaskRepositoryInterface;
use App\Task\ValueObject\ActivityStatus;
use App\Task\ValueObject\TaskType;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PlayerSaldoProviderTest extends TestCase
{
    private PlayerSaldoProvider $saldoProvider;
    private MockObject|TaskRepositoryInterface $taskRepository;
    private MockObject|TaskActivityRepositoryInterface $taskActivityRepository;

    protected function setUp(): void
    {
        $this->taskRepository = $this->createMock(TaskRepositoryInterface::class);
        $this->taskActivityRepository = $this->createMock(TaskActivityRepositoryInterface::class);
        $this->saldoProvider = new PlayerSaldoProvider(
            $this->taskRepository,
            $this->taskActivityRepository
        );
    }

    public function testCalculateSaldoWithNoTasks(): void
    {
        $player = $this->createMock(Player::class);

        $this->taskRepository
            ->expects($this->once())
            ->method('findByPlayer')
            ->with($player)
            ->willReturn([]);

        $this->taskActivityRepository
            ->expects($this->never())
            ->method('findActivitiesForSaldoCalculation');

        $saldo = $this->saldoProvider->calculateSaldo($player);

        $this->assertEquals(0, $saldo);
    }

    public function testCalculateSaldoWithTasksButNoActivities(): void
    {
        $player = $this->createMock(Player::class);

        $tasks = [
            $this->createTask(TaskType::Activity, 100), // No penalty for activity without activities
            $this->createTask(TaskType::Duty, 50),      // -50 penalty for duty without activities
            $this->createTask(TaskType::Duty, 30),      // -30 penalty for duty without activities
        ];

        $this->taskRepository
            ->expects($this->once())
            ->method('findByPlayer')
            ->with($player)
            ->willReturn($tasks);

        $this->taskActivityRepository
            ->expects($this->exactly(3))
            ->method('findActivitiesForSaldoCalculation')
            ->with($player)
            ->willReturn([]); // No activities for any task

        $saldo = $this->saldoProvider->calculateSaldo($player);

        // Expected: 0 + 0 - 50 - 30 = -80
        $this->assertEquals(-80, $saldo);
    }

    public function testCalculateSaldoWithMixedTasksAndActivities(): void
    {
        $player = $this->createMock(Player::class);

        $task1 = $this->createTask(TaskType::Activity, 100);
        $task2 = $this->createTask(TaskType::Activity, 50);
        $task3 = $this->createTask(TaskType::Duty, 30);
        $task4 = $this->createTask(TaskType::Duty, 20);
        $task5 = $this->createTask(TaskType::Duty, 40); // This will have no activities

        $tasks = [$task1, $task2, $task3, $task4, $task5];

        $activities = [
            $this->createActivity($task1, ActivityStatus::Finished), // +100
            $this->createActivity($task2, ActivityStatus::Expired),  // -50
            $this->createActivity($task3, ActivityStatus::InProgress), // -30 (not completed)
            $this->createActivity($task4, ActivityStatus::Finished),   // +0 (completed, no penalty)
            // task5 has no activities - will be -40 penalty for duty
        ];

        $this->taskRepository
            ->expects($this->once())
            ->method('findByPlayer')
            ->with($player)
            ->willReturn($tasks);

        $this->taskActivityRepository
            ->expects($this->exactly(5))
            ->method('findActivitiesForSaldoCalculation')
            ->with($player)
            ->willReturn($activities);

        $saldo = $this->saldoProvider->calculateSaldo($player);

        // Expected: 0 + 100 - 50 - 30 + 0 - 40 = -20
        $this->assertEquals(-20, $saldo);
    }

    private function createTask(TaskType $taskType, int $taskValue)
    {
        $task = new class {
            public TaskType $type;
            public int $value;
            public object $id;
        };
        $task->type = $taskType;
        $task->value = $taskValue;

        // Create a mock ID object with equals method
        $task->id = new class {
            public function equals($other): bool {
                return $this === $other;
            }
        };

        return $task;
    }

    private function createActivity($task, ActivityStatus $activityStatus)
    {
        $activity = new class {
            public ActivityStatus $status;
            public object $task;
        };
        $activity->status = $activityStatus;
        $activity->task = $task;

        return $activity;
    }
}
