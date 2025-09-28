<?php

declare(strict_types=1);

namespace App\Task\ValueObject;

enum TaskType: string
{
    case Duty = 'duty';
    case Activity = 'activity';
}

