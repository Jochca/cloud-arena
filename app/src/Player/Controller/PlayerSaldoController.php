<?php

declare(strict_types=1);

namespace App\Player\Controller;

use App\Player\Entity\Player;
use App\Player\Repository\PlayerRepositoryInterface;
use App\Player\Service\PlayerSaldoProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class PlayerSaldoController extends AbstractController
{
    public function __construct(
        private readonly PlayerRepositoryInterface $playerRepository,
        private readonly PlayerSaldoProviderInterface $saldoProvider
    ) {}

    #[Route('/{uuid}/saldo', methods: ['GET'])]
    public function getSaldo(string $uuid): JsonResponse
    {
        $playerId = Uuid::fromString($uuid);

        /** @var Player|null $player */
        $player = $this->playerRepository->find($playerId);

        if (!$player) {
            return $this->json(['error' => 'Player not found'], 404);
        }

        $saldo = $this->saldoProvider->calculateSaldo($player);

        return $this->json([
            'player_id' => $player->id->toRfc4122(),
            'saldo' => $saldo
        ]);
    }
}
