<?php

declare(strict_types=1);

namespace App\Player\Service;

use App\Player\Entity\Player;
use App\Session\Entity\SessionScoring;
use Doctrine\ORM\EntityManagerInterface;

class PlayerService implements PlayerServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function calculatePlayerBalance(Player $player): int
    {
        $session = $player->session;

        // Get all session scorings for this session
        $scorings = $this->entityManager->getRepository(SessionScoring::class)
            ->findBy(['session' => $session]);

        $balance = 0;

        foreach ($scorings as $scoring) {
            if ($scoring->winner->id->equals($player->id)) {
                $balance += $scoring->winnerScore;
            }
            if ($scoring->looser->id->equals($player->id)) {
                $balance += $scoring->looserScore;
            }
        }

        return $balance;
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
