<?php

declare(strict_types=1);

namespace App\Session\Payload;

use Symfony\Component\Validator\Constraints as Assert;

class LoginPayload
{
    #[Assert\NotBlank(message: 'Key is required.')]
    #[Assert\Type(type: 'integer', message: 'Key must be an integer.')]
    public int $key;
}
