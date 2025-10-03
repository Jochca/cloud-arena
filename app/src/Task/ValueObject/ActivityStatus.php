<?php

declare(strict_types=1);

namespace App\Task\ValueObject;

enum ActivityStatus: string
{
    case InProgress = 'in_progress';
    case Finished = 'finished';
    case Expired = 'expired';
}
