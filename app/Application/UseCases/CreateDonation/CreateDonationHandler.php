<?php

declare(strict_types=1);

namespace App\Application\UseCases\CreateDonation;

use App\Domain\Common\ValueObject\Money;
use App\Domain\Donation\Donation;
use App\Domain\Donation\DonationRepositoryInterface;
use App\Domain\Donation\Events\DonationCreated;
use DateTimeImmutable;
use Illuminate\Contracts\Events\Dispatcher;
use Psr\Log\LoggerInterface;

final readonly class CreateDonationHandler
{
    public function __construct(
        private DonationRepositoryInterface $repository,
        private LoggerInterface $logger,
        private Dispatcher $dispatcher,
    ) {}

    public function handle(int $campaignId, int $employeeId, Money $amount): Donation
    {
        $donation = $this->repository->save(
            new Donation(
                id: null,
                campaignId: $campaignId,
                employeeId: $employeeId,
                amount: $amount,
                createdAt: new DateTimeImmutable,
            )
        );

        $this->logger->info('Donation created successfully', [
            'donation_id' => $donation->id,
            'employee_id' => $donation->employeeId,
            'campaign_id' => $donation->campaignId,
            'amount' => $donation->amount->toString(),
        ]);

        try {
            $this->dispatcher->dispatch(new DonationCreated($donation));
        } catch (\Throwable $e) {
            $this->logger->error('Failed to dispatch DonationCreated event', [
                'donation_id' => $donation->id,
                'exception' => $e->getMessage(),
            ]);
        }

        return $donation;
    }
}
