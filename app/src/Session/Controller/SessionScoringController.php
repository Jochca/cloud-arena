<?php

declare(strict_types=1);

namespace App\Session\Controller;

use App\Player\Entity\Player;
use App\Player\Repository\PlayerRepositoryInterface;
use App\Session\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class SessionScoringController extends AbstractController
{
    public function __construct(
        private readonly SessionRepository $sessionRepository,
        private readonly PlayerRepositoryInterface $playerRepository,
    ) {
    }

    #[Route('/scorings', methods: ['GET'])]
    public function getSessionScorings(): JsonResponse
    {
        // Get player ID from JWT token
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Authentication required.'], 401);
        }

        $playerId = Uuid::fromString($user->getUserIdentifier());

        /** @var Player|null $player */
        $player = $this->playerRepository->find($playerId);
        if (!$player) {
            return $this->json(['error' => 'Player not found.'], 404);
        }

        // Get all session scorings for this player's session
        $sessionScorings = $this->sessionRepository->findSessionScoringsByPlayer($player);

        // Transform to response format
        $scoringsData = array_map(function ($scoring) {
            return [
                'id' => $scoring->id->toRfc4122(),
                'dateStart' => $scoring->dateStart->format('Y-m-d H:i:s'),
                'dateEnd' => $scoring->dateEnd->format('Y-m-d H:i:s'),
                'winner' => [
                    'id' => $scoring->winner->id->toRfc4122(),
                    'name' => $scoring->winner->name,
                ],
                'winnerScore' => $scoring->winnerScore,
                'looser' => [
                    'id' => $scoring->looser->id->toRfc4122(),
                    'name' => $scoring->looser->name,
                ],
                'looserScore' => $scoring->looserScore,
            ];
        }, $sessionScorings);

        return $this->json([
            'sessionScorings' => $scoringsData,
            'count' => count($scoringsData),
        ]);
    }
}
