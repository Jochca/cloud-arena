<?php

declare(strict_types=1);

namespace App\Player\Service;

use App\Player\Entity\Player;

interface PlayerSaldoProviderInterface
{
    public function calculateSaldo(Player $player): int;
}
