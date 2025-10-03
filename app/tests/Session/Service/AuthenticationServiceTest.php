<?php

declare(strict_types=1);

namespace App\Tests\Session\Service;

use App\Session\Repository\SessionPlayerKeyRepositoryInterface;
use App\Session\Service\AuthenticationService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthenticationServiceTest extends TestCase
{
    private AuthenticationService $authenticationService;
    private MockObject|SessionPlayerKeyRepositoryInterface $sessionPlayerKeyRepository;
    private MockObject|JWTTokenManagerInterface $jwtManager;

    protected function setUp(): void
    {
        $this->sessionPlayerKeyRepository = $this->createMock(SessionPlayerKeyRepositoryInterface::class);
        $this->jwtManager = $this->createMock(JWTTokenManagerInterface::class);
        $this->authenticationService = new AuthenticationService(
            $this->sessionPlayerKeyRepository,
            $this->jwtManager
        );
    }

    public function testAuthenticateByKeyWithInvalidKey(): void
    {
        $key = 99999;

        $this->sessionPlayerKeyRepository
            ->expects($this->once())
            ->method('findByKey')
            ->with($key)
            ->willReturn(null);

        $this->jwtManager
            ->expects($this->never())
            ->method('create');

        $result = $this->authenticationService->authenticateByKey($key);

        $this->assertNull($result);
    }

    public function testServiceInstantiation(): void
    {
        // Verify that the service can be instantiated without errors
        $this->assertInstanceOf(AuthenticationService::class, $this->authenticationService);
    }
}
