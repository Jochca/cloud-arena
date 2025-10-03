<?php

declare(strict_types=1);

namespace App\Session\Controller;

use App\Player\Entity\Player;
use App\Player\Exception\PlayerNotFoundException;
use App\Player\Repository\PlayerRepositoryInterface;
use App\Session\DTO\PlayerInfoDTO;
use App\Session\DTO\SessionScoringDTO;
use App\Session\DTO\SessionScoringsResponseDTO;
use App\Session\Exception\AuthenticationRequiredException;
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
        $user = $this->getUser();
        if (!$user) {
            throw new AuthenticationRequiredException();
        }

        $playerId = Uuid::fromString($user->getUserIdentifier());

        $player = $this->playerRepository->find($playerId);
        if (!$player instanceof Player) {
            throw new PlayerNotFoundException();
        }

        $sessionScorings = $this->sessionRepository->findSessionScoringsByPlayer($player);

        $scoringsData = array_map(function ($scoring) {
            return new SessionScoringDTO(
                $scoring->id->toRfc4122(),
                $scoring->dateStart->format('Y-m-d H:i:s'),
                $scoring->dateEnd->format('Y-m-d H:i:s'),
                new PlayerInfoDTO($scoring->winner->id->toRfc4122(), $scoring->winner->name),
                $scoring->winnerScore,
                new PlayerInfoDTO($scoring->looser->id->toRfc4122(), $scoring->looser->name),
                $scoring->looserScore
            );
        }, $sessionScorings);

        $response = new SessionScoringsResponseDTO($scoringsData, count($scoringsData));

        return $this->json($response);
    }
}
