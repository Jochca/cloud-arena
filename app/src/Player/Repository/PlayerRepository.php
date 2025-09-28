<?php

declare(strict_types=1);

namespace App\Player\Repository;

use App\Player\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class PlayerRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function findPlayerById(string $id): ?Player
    {
        return $this->entityManager->getRepository(Player::class)->find($id);
    }
}

