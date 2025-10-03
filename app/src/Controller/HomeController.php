<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Service\HomeDashboardServiceInterface;
use App\Player\Repository\PlayerRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly HomeDashboardServiceInterface $dashboardService,
        private readonly PlayerRepositoryInterface $playerRepository
    ) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/api/dashboard', name: 'api_dashboard', methods: ['GET'])]
    #[IsGranted('ROLE_PLAYER')]
    public function getDashboardData(): JsonResponse
    {
        // Get the current player ID from JWT token
        $playerId = $this->getUser()->getUserIdentifier();
        $player = $this->playerRepository->findPlayerById($playerId);

        if (!$player) {
            return $this->json(['error' => 'Player not found'], 404);
        }

        $dashboardData = $this->dashboardService->getDashboardData($player);

        return $this->json($dashboardData);
    }
}
