<?php

declare(strict_types=1);

namespace App\Auth\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class InvalidKeyException extends UnauthorizedHttpException
{
    public function __construct()
    {
        parent::__construct('', 'Invalid authentication key provided');
    }
}
