<?php

declare(strict_types=1);

namespace App\Session\Repository;

use App\Session\Entity\SessionPlayerKey;

interface SessionPlayerKeyRepositoryInterface
{
    public function findByKey(int $key): ?SessionPlayerKey;
}
