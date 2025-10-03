<?php

declare(strict_types=1);

namespace App\Session\Service;

use App\Session\Repository\SessionPlayerKeyRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\InMemoryUser;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function __construct(
        private readonly SessionPlayerKeyRepositoryInterface $sessionPlayerKeyRepository,
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function authenticateByKey(int $key): ?string
    {
        $sessionPlayerKey = $this->sessionPlayerKeyRepository->findByKey($key);

        if (!$sessionPlayerKey) {
            return null;
        }

        // Create a temporary user object with player ID as username
        $user = new InMemoryUser(
            $sessionPlayerKey->player->id->toRfc4122(),
            '',
            ['ROLE_PLAYER']
        );

        // Create JWT token with player ID in payload
        return $this->jwtManager->create($user);
    }
}
