<?php

declare(strict_types=1);

namespace App\Controller\DTO;

class TaskDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $value,
        public readonly string $status,
        public readonly string $type,
        public readonly string $button_text,
    ) {
    }
}
