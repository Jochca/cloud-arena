<?php

declare(strict_types=1);

namespace App\Controller\Service;

use App\Player\Entity\Player;
use App\Player\Service\PlayerServiceInterface;
use App\Session\Repository\SessionRepositoryInterface;
use App\Task\Repository\TaskRepositoryInterface;
use App\Task\ValueObject\TaskStatus;

class HomeDashboardService implements HomeDashboardServiceInterface
{
    public function __construct(
        private PlayerServiceInterface $playerService,
        private SessionRepositoryInterface $sessionRepository,
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function getDashboardData(Player $currentPlayer): array
    {
        $session = $currentPlayer->session;
        $otherPlayer = $this->playerService->getOtherPlayerInSession($currentPlayer);

        // Calculate balances
        $currentPlayerBalance = $this->playerService->calculatePlayerBalance($currentPlayer);
        $otherPlayerBalance = $otherPlayer ? $this->playerService->calculatePlayerBalance($otherPlayer) : 0;

        // Get round count (number of session scorings + 1)
        $roundCount = $this->sessionRepository->countSessionScorings($session) + 1;

        // Get all tasks and categorize them
        $allTasks = $this->taskRepository->findBySession($session);
        $currentPlayerTasks = $this->taskRepository->findByPlayer($currentPlayer);
        $otherPlayerTasks = $otherPlayer ? $this->taskRepository->findByPlayer($otherPlayer) : [];
        $freeTasks = $this->taskRepository->findFreeTasksBySession($session);

        // Convert tasks to arrays with button text
        $tasksData = [
            'your_tasks' => $this->formatTasksForDisplay($currentPlayerTasks),
            'free_tasks' => $this->formatTasksForDisplay($freeTasks),
            'other_player_tasks' => $this->formatTasksForDisplay($otherPlayerTasks)
        ];

        return [
            'balances' => [
                'current_player' => $currentPlayerBalance,
                'other_player' => $otherPlayerBalance,
                'other_player_name' => $otherPlayer?->name ?? 'Unknown'
            ],
            'tasks' => $tasksData,
            'round_count' => $roundCount
        ];
    }

    private function formatTasksForDisplay(array $tasks): array
    {
        return array_map(function($task) {
            return [
                'id' => (string) $task->id,
                'name' => $task->name,
                'description' => $task->description,
                'value' => $task->value,
                'status' => $task->status->value,
                'button_text' => $this->getButtonTextForStatus($task->status)
            ];
        }, $tasks);
    }

    private function getButtonTextForStatus(TaskStatus $status): string
    {
        return match($status) {
            TaskStatus::Pending => 'Start',
            TaskStatus::InProgress => 'Complete',
            TaskStatus::Completed => 'Completed'
        };
    }
}
