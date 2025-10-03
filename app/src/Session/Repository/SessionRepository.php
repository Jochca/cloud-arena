<?php

declare(strict_types=1);

namespace App\Session\Repository;

use App\Player\Entity\Player;
use App\Session\Entity\Session;
use App\Session\Entity\SessionScoring;
use Doctrine\ORM\EntityManagerInterface;

class SessionRepository implements SessionRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findSessionByPlayer(Player $player): ?Session
    {
        // Since Player has a ManyToOne relationship with Session,
        // we can directly access the session from the player
        return $player->session;
    }

    public function countSessionScorings(Session $session): int
    {
        return $this->entityManager->getRepository(SessionScoring::class)->count(['session' => $session]);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Session::class)->findAll();
    }

    public function findSessionScoringsByPlayer(Player $player): array
    {
        // First find the session for this player
        $session = $this->findSessionByPlayer($player);

        if (!$session) {
            return [];
        }

        // Then find all SessionScorings for that session
        return $this->entityManager->getRepository(SessionScoring::class)
            ->findBy(['session' => $session], ['dateStart' => 'DESC']);
    }
}
