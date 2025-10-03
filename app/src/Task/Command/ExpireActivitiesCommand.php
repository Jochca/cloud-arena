<?php

declare(strict_types=1);

namespace App\Task\Command;

use App\Task\Service\ActivityExpirationServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'task:expire-activities',
    description: 'Expire activities that have passed their end date and reset their task status to pending'
)]
class ExpireActivitiesCommand extends Command
{
    public function __construct(
        private readonly ActivityExpirationServiceInterface $activityExpirationService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Expiring Activities');
        $io->text('Checking for activities that have passed their end date...');

        $expiredCount = $this->activityExpirationService->expireActivities();

        if ($expiredCount > 0) {
            $io->success(sprintf('Successfully expired %d activities and reset their task status to pending.', $expiredCount));
        } else {
            $io->info('No activities found to expire.');
        }

        return Command::SUCCESS;
    }
}
