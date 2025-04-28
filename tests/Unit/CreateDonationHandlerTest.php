<?php

declare(strict_types=1);

use App\Application\UseCases\CreateDonation\CreateDonationHandler;
use App\Domain\Common\ValueObject\Money;
use App\Domain\Donation\Donation;
use App\Domain\Donation\DonationRepositoryInterface;
use App\Domain\Donation\Events\DonationCreated;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;

/** @var DonationRepositoryInterface&MockInterface $repository */
$repository = Mockery::mock(DonationRepositoryInterface::class);
/** @var LoggerInterface&MockInterface $logger */
$logger = Mockery::mock(LoggerInterface::class);
/** @var Dispatcher&MockInterface $dispatcher */
$dispatcher = Mockery::mock(Dispatcher::class);
/** @var CreateDonationHandler $handler */
$handler = new CreateDonationHandler($repository, $logger, $dispatcher);

beforeEach(function () use (&$repository, &$logger, &$dispatcher, &$handler) {
    $repository = Mockery::mock(DonationRepositoryInterface::class);
    $logger = Mockery::mock(LoggerInterface::class);
    $dispatcher = Mockery::mock(Dispatcher::class);
    $handler = new CreateDonationHandler($repository, $logger, $dispatcher);
});

test('it creates a new donation successfully', function () use (&$repository, &$logger, &$dispatcher, &$handler) {
    $repository->expects('save')
        ->andReturnUsing(fn (Donation $donation) => new Donation(
            id: 1,
            campaignId: $donation->campaignId,
            employeeId: $donation->employeeId,
            amount: $donation->amount,
            createdAt: $donation->createdAt,
        ));

    $logger->expects('info')
        ->with('Donation created successfully', Mockery::type('array'));

    $logger->allows('error')->never();

    $dispatcher->expects('dispatch')
        ->with(Mockery::type(DonationCreated::class));

    $donation = $handler->handle(
        campaignId: 1,
        employeeId: 1,
        amount: Money::fromString('25.00')
    );

    expect($donation->id)->toBe(1)
        ->and($donation->campaignId)->toBe(1)
        ->and($donation->employeeId)->toBe(1)
        ->and($donation->amount->toString())->toBe('25.00');
});
