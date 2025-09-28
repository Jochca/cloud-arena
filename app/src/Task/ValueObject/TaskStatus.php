<?php

declare(strict_types=1);

namespace App\Task\ValueObject;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Finished = 'finished';
}
