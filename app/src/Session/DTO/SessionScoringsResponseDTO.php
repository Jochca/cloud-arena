<?php

declare(strict_types=1);

namespace App\Session\DTO;

class SessionScoringsResponseDTO
{
    /**
     * @param SessionScoringDTO[] $sessionScorings
     */
    public function __construct(
        public readonly array $sessionScorings,
        public readonly int $count,
    ) {
    }
}
