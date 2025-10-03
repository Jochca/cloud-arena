<?php

declare(strict_types=1);

namespace App\Session\Service;

use App\Player\Service\PlayerSaldoProviderInterface;
use App\Session\Entity\Session;
use App\Session\Entity\SessionScoring;
use App\Session\Repository\SessionRepository;
use App\Task\ValueObject\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class SessionScoringCreatorService implements SessionScoringCreatorServiceInterface
{
    public function __construct(
        private readonly SessionRepository $sessionRepository,
        private readonly PlayerSaldoProviderInterface $saldoProvider,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createWeeklyScoring(): int
    {
        $today = new \DateTimeImmutable();

        // Check if today is Sunday (last day of the week)
        if ('0' !== $today->format('w')) {
            return 0; // Not Sunday, no scoring created
        }

        $sessions = $this->sessionRepository->findAll();
        $scoringCount = 0;

        foreach ($sessions as $session) {
            $players = $session->getPlayers()->toArray();

            // Skip sessions that don't have exactly 2 players
            if (2 !== count($players)) {
                continue;
            }

            $player1 = $players[0];
            $player2 = $players[1];

            // Calculate saldo for both players
            $player1Saldo = $this->saldoProvider->calculateSaldo($player1);
            $player2Saldo = $this->saldoProvider->calculateSaldo($player2);

            // Determine winner and looser
            if ($player1Saldo > $player2Saldo) {
                $winner = $player1;
                $winnerScore = $player1Saldo;
                $looser = $player2;
                $looserScore = $player2Saldo;
            } else {
                $winner = $player2;
                $winnerScore = $player2Saldo;
                $looser = $player1;
                $looserScore = $player1Saldo;
            }

            // Create SessionScoring object
            $scoring = new SessionScoring();
            $scoring->id = Uuid::v7();
            $scoring->session = $session;
            $scoring->winner = $winner;
            $scoring->winnerScore = $winnerScore;
            $scoring->looser = $looser;
            $scoring->looserScore = $looserScore;
            $scoring->dateStart = $today;
            $scoring->dateEnd = $today;

            $this->entityManager->persist($scoring);

            // Link all tasks and activities from this session to the scoring
            $this->linkTasksAndActivitiesToScoring($session, $scoring);

            ++$scoringCount;
        }

        if ($scoringCount > 0) {
            $this->entityManager->flush();
        }

        return $scoringCount;
    }

    private function linkTasksAndActivitiesToScoring(Session $session, SessionScoring $scoring): void
    {
        $tasks = $session->getTasks()->toArray();

        foreach ($tasks as $task) {
            $task->status = TaskStatus::Pending;

            $activities = $task->getActivities()->toArray();
            foreach ($activities as $activity) {
                $activity->scoring = $scoring;
            }
        }
    }
}
