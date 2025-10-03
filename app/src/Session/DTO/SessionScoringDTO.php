<?php

declare(strict_types=1);

namespace App\Session\DTO;

class SessionScoringDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $dateStart,
        public readonly string $dateEnd,
        public readonly PlayerInfoDTO $winner,
        public readonly int $winnerScore,
        public readonly PlayerInfoDTO $looser,
        public readonly int $looserScore,
    ) {
    }
}
