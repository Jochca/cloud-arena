<?php

declare(strict_types=1);

namespace App\Task\Service;

interface ActivityExpirationServiceInterface
{
    public function expireActivities(): int;
}
