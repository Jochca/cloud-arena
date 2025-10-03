<?php

declare(strict_types=1);

namespace App\Task\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidTaskActionException extends BadRequestHttpException
{
    public function __construct(string $action)
    {
        parent::__construct("Invalid action value: {$action}");
    }
}
