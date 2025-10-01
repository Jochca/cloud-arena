<?php

declare(strict_types=1);

namespace App\Task\ValueObject;

enum TaskAction: string
{
    case Start = 'start';
    case Finish = 'finish';
    case Cancel = 'cancel';
}
