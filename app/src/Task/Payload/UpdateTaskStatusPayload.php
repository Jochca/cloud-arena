<?php

declare(strict_types=1);

namespace App\Task\Payload;

use App\Task\ValueObject\TaskAction;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTaskStatusPayload
{
    #[Assert\NotBlank(message: 'Action is required.')]
    #[Assert\Choice(
        callback: [self::class, 'allowedActions'],
        message: 'Invalid action value. Allowed values are: {{ choices }}.'
    )]
    public string $action;

    public static function allowedActions(): array
    {
        return array_map(fn(TaskAction $a) => $a->value, TaskAction::cases());
    }
}
