<?php

declare(strict_types=1);

namespace App\Session\Controller;

use App\Player\Repository\PlayerRepository;
use App\Session\Repository\SessionRepository;
use App\Task\ValueObject\TaskStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private SessionRepository $sessionRepository,
    ) {
    }

    #[Route('/home', name: 'landing_index')]
    public function index(): Response
    {
        // Temporary solution: Fetch player with fixed ID
        $player = $this->playerRepository->findPlayerById('42e02fdb-140e-44f7-bcff-1cf0a0e20329');

        if (!$player) {
            throw $this->createNotFoundException('Player not found.');
        }

        // Retrieve the session associated with the player
        $session = $this->sessionRepository->findSessionByPlayer($player);

        if (!$session) {
            throw $this->createNotFoundException('Session not found for the player.');
        }

        // Categorize tasks by status
        $tasks = $session->getTasks();
        $categorizedTasks = [
            TaskStatus::Pending->value => [],
            TaskStatus::InProgress->value => [],
            TaskStatus::Completed->value => [],
        ];

        foreach ($tasks as $task) {
            $categorizedTasks[$task->status->value][] = $task;
        }

        // Calculate WeekCount
        $sessionScoringCount = $this->sessionRepository->countSessionScorings($session);
        $weekCount = $sessionScoringCount + 1;

        // Render the view
        return $this->render('home.html.twig', [
            'weekCount' => $weekCount,
            'tasks' => $categorizedTasks,
        ]);
    }
}
