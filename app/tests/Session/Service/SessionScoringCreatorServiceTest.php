<?php

declare(strict_types=1);

namespace App\Tests\Session\Service;

use App\Player\Entity\Player;
use App\Player\Service\PlayerSaldoProviderInterface;
use App\Session\Entity\Session;
use App\Session\Repository\SessionRepository;
use App\Session\Service\SessionScoringCreatorService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SessionScoringCreatorServiceTest extends TestCase
{
    private SessionRepository|MockObject $sessionRepository;
    private PlayerSaldoProviderInterface|MockObject $saldoProvider;
    private EntityManagerInterface|MockObject $entityManager;

    protected function setUp(): void
    {
        $this->sessionRepository = $this->createMock(SessionRepository::class);
        $this->saldoProvider = $this->createMock(PlayerSaldoProviderInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    public function testCreateWeeklyScoringOnNonSunday(): void
    {
        $scoringCreatorService = new SessionScoringCreatorService(
            $this->sessionRepository,
            $this->saldoProvider,
            $this->entityManager
        );

        // Since today is not Sunday, the method should return 0 without calling any repository methods
        $this->sessionRepository
            ->expects($this->never())
            ->method('findAll');

        $result = $scoringCreatorService->createWeeklyScoring();
        $this->assertEquals(0, $result);
    }

    public function testSundayDateDetection(): void
    {
        $today = new \DateTimeImmutable();
        $dayOfWeek = $today->format('w');

        // Verify that today is not Sunday (should be '0' for Sunday)
        $this->assertNotEquals('0', $dayOfWeek, 'Today should not be Sunday for this test');
    }

    public function testServiceInstantiation(): void
    {
        $scoringCreatorService = new SessionScoringCreatorService(
            $this->sessionRepository,
            $this->saldoProvider,
            $this->entityManager
        );

        // Verify that the service can be instantiated without errors
        $this->assertInstanceOf(SessionScoringCreatorService::class, $scoringCreatorService);
    }

    private function createMockPlayer(): MockObject
    {
        return $this->createMock(Player::class);
    }

    private function createMockSession(array $players): MockObject
    {
        $session = $this->createMock(Session::class);
        $playersCollection = new ArrayCollection($players);

        $session->method('getPlayers')->willReturn($playersCollection);
        $session->method('getTasks')->willReturn(new ArrayCollection([]));

        return $session;
    }
}
