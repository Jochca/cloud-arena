<?php

declare(strict_types=1);

namespace App\Task\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskPermissionException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('You can only modify your own tasks');
    }
}
