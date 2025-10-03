<?php

declare(strict_types=1);

namespace App\Player\Service;

use App\Player\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class PlayerService implements PlayerServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getOtherPlayerInSession(Player $currentPlayer): ?Player
    {
        $session = $currentPlayer->session;
        $players = $session->getPlayers();

        foreach ($players as $player) {
            if (!$player->id->equals($currentPlayer->id)) {
                return $player;
            }
        }

        return null;
    }
}
