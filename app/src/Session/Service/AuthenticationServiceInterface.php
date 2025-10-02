<?php

declare(strict_types=1);

namespace App\Session\Service;

interface AuthenticationServiceInterface
{
    public function authenticateByKey(int $key): ?string;
}
