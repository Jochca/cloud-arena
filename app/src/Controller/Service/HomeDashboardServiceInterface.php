<?php

declare(strict_types=1);

namespace App\Controller\Service;

use App\Controller\DTO\DashboardResponseDTO;
use App\Player\Entity\Player;

interface HomeDashboardServiceInterface
{
    public function getDashboardData(Player $currentPlayer): DashboardResponseDTO;
}
