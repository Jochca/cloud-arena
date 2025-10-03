<?php

declare(strict_types=1);

namespace App\Task\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('Task not found');
    }
}
