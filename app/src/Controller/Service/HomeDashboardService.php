<?php

declare(strict_types=1);

namespace App\Controller\Service;

use App\Controller\DTO\DashboardResponseDTO;
use App\Controller\DTO\PlayerBalanceDTO;
use App\Controller\DTO\TaskCategoriesDTO;
use App\Controller\DTO\TaskDTO;
use App\Player\Entity\Player;
use App\Player\Service\PlayerSaldoProviderInterface;
use App\Player\Service\PlayerServiceInterface;
use App\Session\Repository\SessionRepositoryInterface;
use App\Task\Repository\TaskRepositoryInterface;
use App\Task\ValueObject\TaskStatus;

class HomeDashboardService implements HomeDashboardServiceInterface
{
    public function __construct(
        private PlayerServiceInterface $playerService,
        private SessionRepositoryInterface $sessionRepository,
        private TaskRepositoryInterface $taskRepository,
        private PlayerSaldoProviderInterface $playerSaldoProvider,
    ) {
    }

    public function getDashboardData(Player $currentPlayer): DashboardResponseDTO
    {
        $session = $currentPlayer->session;
        $otherPlayer = $this->playerService->getOtherPlayerInSession($currentPlayer);

        $currentPlayerBalance = $this->playerSaldoProvider->calculateSaldo($currentPlayer);
        $otherPlayerBalance = $otherPlayer ? $this->playerSaldoProvider->calculateSaldo($otherPlayer) : 0;

        $roundCount = $this->sessionRepository->countSessionScorings($session) + 1;

        $currentPlayerTasks = $this->taskRepository->findByPlayer($currentPlayer);
        $otherPlayerTasks = $otherPlayer ? $this->taskRepository->findByPlayer($otherPlayer) : [];
        $freeTasks = $this->taskRepository->findFreeTasksBySession($session);

        $balances = new PlayerBalanceDTO(
            $currentPlayerBalance,
            $otherPlayerBalance,
            $otherPlayer?->name ?? 'Unknown'
        );

        $tasks = new TaskCategoriesDTO(
            $this->formatTasksForDisplay($currentPlayerTasks),
            $this->formatTasksForDisplay($freeTasks),
            $this->formatTasksForDisplay($otherPlayerTasks)
        );

        return new DashboardResponseDTO($balances, $tasks, $roundCount);
    }

    /**
     * @param Task[] $tasks
     *
     * @return TaskDTO[]
     */
    private function formatTasksForDisplay(array $tasks): array
    {
        return array_map(function ($task) {
            return new TaskDTO(
                (string)$task->id,
                $task->name,
                $task->description,
                $task->value,
                $task->status->value,
                $task->type->value,
                $this->getButtonTextForStatus($task->status)
            );
        }, $tasks);
    }

    private function getButtonTextForStatus(TaskStatus $status): string
    {
        return match ($status) {
            TaskStatus::Pending => 'Start',
            TaskStatus::InProgress => 'Complete',
            TaskStatus::Completed => 'Completed',
        };
    }
}
