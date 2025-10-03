<?php

declare(strict_types=1);

namespace App\Task\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskStatusViolationException extends BadRequestHttpException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
