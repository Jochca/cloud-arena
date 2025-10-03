<?php

declare(strict_types=1);

namespace App\Session\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticationRequiredException extends UnauthorizedHttpException
{
    public function __construct()
    {
        parent::__construct('', 'Authentication required');
    }
}
