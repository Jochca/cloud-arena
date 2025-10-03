<?php

declare(strict_types=1);

namespace App\Session\Command;

use App\Session\Service\SessionScoringCreatorServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'session:create-weekly-scoring',
    description: 'Create session scoring objects on Sunday based on player saldos'
)]
class CreateWeeklyScoringCommand extends Command
{
    public function __construct(
        private readonly SessionScoringCreatorServiceInterface $scoringCreatorService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Creating Weekly Session Scoring');

        $today = new \DateTimeImmutable();
        $dayOfWeek = $today->format('l'); // Full day name

        $io->text(sprintf('Today is %s (%s)', $dayOfWeek, $today->format('Y-m-d')));

        if ('0' !== $today->format('w')) {
            $io->info('Scoring creation is only available on Sundays. Skipping...');

            return Command::SUCCESS;
        }

        $io->text('Processing sessions for weekly scoring...');

        $scoringCount = $this->scoringCreatorService->createWeeklyScoring();

        if ($scoringCount > 0) {
            $io->success(sprintf('Successfully created %d session scoring records.', $scoringCount));
        } else {
            $io->info('No session scoring records were created (no valid sessions found).');
        }

        return Command::SUCCESS;
    }
}
