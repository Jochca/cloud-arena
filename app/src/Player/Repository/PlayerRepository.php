<?php

declare(strict_types=1);

namespace App\Player\Repository;

use App\Player\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PlayerRepository extends EntityRepository implements PlayerRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(Player::class));
    }

    public function findPlayerById(string $id): ?Player
    {
        return $this->find($id);
    }
}
