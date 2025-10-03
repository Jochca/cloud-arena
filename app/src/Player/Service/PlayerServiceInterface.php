<?php

declare(strict_types=1);

namespace App\Player\Service;

use App\Player\Entity\Player;

interface PlayerServiceInterface
{
    public function calculatePlayerBalance(Player $player): int;

    public function getOtherPlayerInSession(Player $currentPlayer): ?Player;
}
