<?php

declare(strict_types=1);

namespace App\Session\Repository;

use App\Player\Entity\Player;
use App\Session\Entity\Session;
use App\Session\Entity\SessionScoring;
use Doctrine\ORM\EntityManagerInterface;

class SessionRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function findSessionByPlayer(Player $player): ?Session
    {
        return $this->entityManager->getRepository(Session::class)->findOneBy(['player' => $player]);
    }

    public function countSessionScorings(Session $session): int
    {
        return $this->entityManager->getRepository(SessionScoring::class)->count(['session' => $session]);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Session::class)->findAll();
    }
}
