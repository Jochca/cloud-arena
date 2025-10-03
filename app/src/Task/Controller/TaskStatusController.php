<?php

declare(strict_types=1);

namespace App\Task\Controller;

use App\Player\Entity\Player;
use App\Player\Repository\PlayerRepositoryInterface;
use App\Task\Entity\Task;
use App\Task\Payload\UpdateTaskStatusPayload;
use App\Task\Repository\TaskRepository;
use App\Task\Service\TaskActivityService;
use App\Task\ValueObject\TaskAction;
use App\Task\ValueObject\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function update(string $uuid, #[MapRequestPayload] UpdateTaskStatusPayload $payload): Response
    {
        // Get player ID from JWT token (username contains the player UUID)
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Authentication required.'], Response::HTTP_UNAUTHORIZED);
        }

        $playerId = Uuid::fromString($user->getUserIdentifier());

        /* @var Task $task */
        $task = $this->taskRepository->find($uuid);
        if (!$task) {
            return $this->json(['error' => 'Task not found.'], Response::HTTP_NOT_FOUND);
        }

        /* @var Player $player */
        $player = $this->playerRepository->find($playerId);
        if (!$player) {
            return $this->json(['error' => 'Player not found.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $action = TaskAction::from($payload->action);
        } catch (\ValueError $e) {
            return $this->json(['error' => 'Invalid action value.'], Response::HTTP_BAD_REQUEST);
        }

        // Validate action based on current task status
        switch ($action) {
            case TaskAction::Start:
                if (TaskStatus::Pending !== $task->status) {
                    return $this->json(['error' => 'Can only start tasks with pending status.'], Response::HTTP_BAD_REQUEST);
                }

                $taskActivity = $this->taskActivityService->createTaskActivity($task, $player);
                if (!$taskActivity) {
                    return $this->json(['error' => 'Cannot start task - active activity already exists.'], Response::HTTP_CONFLICT);
                }

                $task->status = TaskStatus::InProgress;
                break;

            case TaskAction::Finish:
                if (TaskStatus::InProgress !== $task->status) {
                    return $this->json(['error' => 'Can only finish tasks with in_progress status.'], Response::HTTP_BAD_REQUEST);
                }

                $taskActivity = $this->taskActivityService->finishTaskActivity($task, $player);
                if (!$taskActivity) {
                    return $this->json(['error' => 'Cannot finish task - no active activity found.'], Response::HTTP_CONFLICT);
                }

                $task->status = TaskStatus::Completed;
                break;

            case TaskAction::Cancel:
                if (TaskStatus::InProgress !== $task->status) {
                    return $this->json(['error' => 'Can only cancel tasks with in_progress status.'], Response::HTTP_BAD_REQUEST);
                }

                $taskActivity = $this->taskActivityService->cancelTaskActivity($task, $player);
                if (!$taskActivity) {
                    return $this->json(['error' => 'Cannot cancel task - no active activity found.'], Response::HTTP_CONFLICT);
                }

                $task->status = TaskStatus::Pending;
                break;
        }

        if (null === $task->player) {
            $task->player = $player;
        } elseif ($task->player->id !== $player->id) {
            return $this->json(['error' => 'You can only modify your own tasks.'], Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->json(['message' => 'Task action executed successfully.']);
    }
}
