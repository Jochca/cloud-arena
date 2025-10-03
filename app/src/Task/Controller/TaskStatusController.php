<?php

declare(strict_types=1);

namespace App\Task\Controller;

use App\Player\Entity\Player;
use App\Player\Exception\PlayerNotFoundException;
use App\Player\Repository\PlayerRepositoryInterface;
use App\Session\Exception\AuthenticationRequiredException;
use App\Task\DTO\TaskActionResponseDTO;
use App\Task\Entity\Task;
use App\Task\Exception\InvalidTaskActionException;
use App\Task\Exception\TaskActivityConflictException;
use App\Task\Exception\TaskNotFoundException;
use App\Task\Exception\TaskPermissionException;
use App\Task\Exception\TaskStatusViolationException;
use App\Task\Payload\UpdateTaskStatusPayload;
use App\Task\Repository\TaskRepository;
use App\Task\Service\TaskActivityService;
use App\Task\ValueObject\TaskAction;
use App\Task\ValueObject\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class TaskStatusController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepository,
        private PlayerRepositoryInterface $playerRepository,
        private TaskActivityService $taskActivityService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/{uuid}', name: 'task_update_status', methods: ['PUT'])]
    public function update(string $uuid, #[MapRequestPayload] UpdateTaskStatusPayload $payload): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AuthenticationRequiredException();
        }

        $playerId = Uuid::fromString($user->getUserIdentifier());

        $task = $this->taskRepository->find($uuid);
        if (!$task instanceof Task) {
            throw new TaskNotFoundException();
        }

        $player = $this->playerRepository->find($playerId);
        if (!$player instanceof Player) {
            throw new PlayerNotFoundException();
        }

        try {
            $action = TaskAction::from($payload->action);
        } catch (\ValueError $e) {
            throw new InvalidTaskActionException($payload->action);
        }

        switch ($action) {
            case TaskAction::Start:
                if (TaskStatus::Pending !== $task->status) {
                    throw new TaskStatusViolationException('Can only start tasks with pending status');
                }

                $taskActivity = $this->taskActivityService->createTaskActivity($task, $player);
                if (!$taskActivity) {
                    throw new TaskActivityConflictException('Cannot start task - active activity already exists');
                }

                $task->status = TaskStatus::InProgress;
                break;

            case TaskAction::Finish:
                if (TaskStatus::InProgress !== $task->status) {
                    throw new TaskStatusViolationException('Can only finish tasks with in_progress status');
                }

                $taskActivity = $this->taskActivityService->finishTaskActivity($task, $player);
                if (!$taskActivity) {
                    throw new TaskActivityConflictException('Cannot finish task - no active activity found');
                }

                $task->status = TaskStatus::Completed;
                break;

            case TaskAction::Cancel:
                if (TaskStatus::InProgress !== $task->status) {
                    throw new TaskStatusViolationException('Can only cancel tasks with in_progress status');
                }

                $taskActivity = $this->taskActivityService->cancelTaskActivity($task, $player);
                if (!$taskActivity) {
                    throw new TaskActivityConflictException('Cannot cancel task - no active activity found');
                }

                $task->status = TaskStatus::Pending;
                break;
        }

        if (null === $task->player) {
            $task->player = $player;
        } elseif ($task->player->id !== $player->id) {
            throw new TaskPermissionException();
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $response = new TaskActionResponseDTO('Task action executed successfully');
    }
}
