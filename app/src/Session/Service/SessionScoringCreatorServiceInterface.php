<?php

declare(strict_types=1);

namespace App\Session\Service;

interface SessionScoringCreatorServiceInterface
{
    public function createWeeklyScoring(): int;
}
