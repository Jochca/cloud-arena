<?php

declare(strict_types=1);

namespace App\Session\Repository;

use App\Player\Entity\Player;
use App\Session\Entity\Session;

interface SessionRepositoryInterface
{
    public function findSessionByPlayer(Player $player): ?Session;
    public function countSessionScorings(Session $session): int;
    public function findSessionScoringsByPlayer(Player $player): array;
}
