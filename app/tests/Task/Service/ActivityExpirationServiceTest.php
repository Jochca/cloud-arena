<?php

declare(strict_types=1);

namespace App\Tests\Task\Service;

use App\Task\Repository\TaskActivityRepositoryInterface;
use App\Task\Service\ActivityExpirationService;
use App\Task\ValueObject\ActivityStatus;
use App\Task\ValueObject\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ActivityExpirationServiceTest extends TestCase
{
    private ActivityExpirationService $activityExpirationService;
    private MockObject|TaskActivityRepositoryInterface $taskActivityRepository;
    private MockObject|EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->taskActivityRepository = $this->createMock(TaskActivityRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->activityExpirationService = new ActivityExpirationService(
            $this->taskActivityRepository,
            $this->entityManager
        );
    }

    public function testExpireActivitiesWithNoExpiredActivities(): void
    {
        $this->taskActivityRepository
            ->expects($this->once())
            ->method('findExpiredActivities')
            ->willReturn([]);

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $result = $this->activityExpirationService->expireActivities();

        $this->assertEquals(0, $result);
    }

    public function testExpireActivitiesWithExpiredActivities(): void
    {
        $activities = [
            $this->createExpiredActivity(),
            $this->createExpiredActivity(),
            $this->createExpiredActivity(),
        ];

        $this->taskActivityRepository
            ->expects($this->once())
            ->method('findExpiredActivities')
            ->willReturn($activities);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->activityExpirationService->expireActivities();

        $this->assertEquals(3, $result);

        // Verify that each activity status was changed to Expired
        foreach ($activities as $activity) {
            $this->assertEquals(ActivityStatus::Expired, $activity->status);
            $this->assertEquals(TaskStatus::Pending, $activity->task->status);
        }
    }

    private function createExpiredActivity()
    {
        $task = new class {
            public TaskStatus $status;
        };
        $task->status = TaskStatus::InProgress;

        $activity = new class {
            public ActivityStatus $status;
            public object $task;
        };
        $activity->status = ActivityStatus::InProgress;
        $activity->task = $task;

        return $activity;
    }
}
