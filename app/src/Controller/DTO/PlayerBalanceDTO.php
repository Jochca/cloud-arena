<?php

declare(strict_types=1);

namespace App\Controller\DTO;

class PlayerBalanceDTO
{
    public function __construct(
        public readonly int $current_player,
        public readonly int $other_player,
        public readonly string $other_player_name,
    ) {
    }
}
