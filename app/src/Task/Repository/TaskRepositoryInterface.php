<?php

declare(strict_types=1);

namespace App\Task\Repository;

use App\Player\Entity\Player;
use App\Session\Entity\Session;

interface TaskRepositoryInterface
{
    public function findByPlayer(Player $player): array;
    public function findBySession(Session $session): array;
    public function findFreeTasksBySession(Session $session): array;
}
