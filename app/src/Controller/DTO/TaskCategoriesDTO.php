<?php

declare(strict_types=1);

namespace App\Controller\DTO;

class TaskCategoriesDTO
{
    /**
     * @param TaskDTO[] $your_tasks
     * @param TaskDTO[] $free_tasks
     * @param TaskDTO[] $other_player_tasks
     */
    public function __construct(
        public readonly array $your_tasks,
        public readonly array $free_tasks,
        public readonly array $other_player_tasks,
    ) {
    }
}
