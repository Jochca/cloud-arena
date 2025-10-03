<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\Session\Payload\LoginPayload;
use App\Session\Service\AuthenticationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationServiceInterface $authenticationService,
    ) {
    }

    #[Route('/login', methods: ['POST'])]
    public function login(#[MapRequestPayload] LoginPayload $payload): JsonResponse
    {
        $token = $this->authenticationService->authenticateByKey($payload->key);

        if (!$token) {
            return $this->json(['error' => 'Invalid key'], 401);
        }

        return $this->json([
            'token' => $token,
            'message' => 'Authentication successful',
        ]);
    }
}
