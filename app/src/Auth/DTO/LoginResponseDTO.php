<?php

declare(strict_types=1);

namespace App\Auth\DTO;

class LoginResponseDTO
{
    public function __construct(
        public readonly string $token,
        public readonly string $message,
    ) {
    }
}
