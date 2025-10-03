<?php

declare(strict_types=1);

namespace App\Controller\DTO;

class DashboardResponseDTO
{
    public function __construct(
        public readonly PlayerBalanceDTO $balances,
        public readonly TaskCategoriesDTO $tasks,
        public readonly int $round_count,
    ) {
    }
}
