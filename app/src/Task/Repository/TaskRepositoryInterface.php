<?php

declare(strict_types=1);

namespace App\Task\Repository;

use App\Player\Entity\Player;

interface TaskRepositoryInterface
{
    public function findByPlayer(Player $player): array;
}
