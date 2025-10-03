<?php

declare(strict_types=1);

namespace App\Task\Repository;

use App\Player\Entity\Player;

interface TaskActivityRepositoryInterface
{
    public function findActivitiesForSaldoCalculation(Player $player): array;

    public function findExpiredActivities(): array;
}
