<?php

declare(strict_types=1);

use App\Application\UseCases\CreateCampaign\CreateCampaignHandler;
use App\Domain\Campaign\Campaign;
use App\Domain\Campaign\CampaignRepositoryInterface;
use App\Domain\Campaign\CampaignStatus;
use App\Domain\Common\ValueObject\Money;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;

// Define test variables
/** @var CampaignRepositoryInterface&MockInterface $repository */
$repository = Mockery::mock(CampaignRepositoryInterface::class);
/** @var LoggerInterface&MockInterface $logger */
$logger = Mockery::mock(LoggerInterface::class);
/** @var CreateCampaignHandler $handler */
$handler = new CreateCampaignHandler($repository, $logger);

beforeEach(function () use (&$repository, &$logger, &$handler) {
    $repository = Mockery::mock(CampaignRepositoryInterface::class);
    $logger = Mockery::mock(LoggerInterface::class);
    $handler = new CreateCampaignHandler($repository, $logger);
});

test('it creates a new campaign successfully', function () use (&$repository, &$logger, &$handler) {
    $repository->expects('save')
        ->andReturnUsing(fn (Campaign $campaign) => new Campaign(
            id: 1,
            title: $campaign->title,
            description: $campaign->description,
            goalAmount: $campaign->goalAmount,
            ownerId: $campaign->ownerId,
            status: $campaign->status,
            createdAt: new DateTimeImmutable,
            updatedAt: new DateTimeImmutable,
        ));

    $logger->expects('info')
        ->with('Campaign created successfully', Mockery::type('array'));

    $campaign = $handler->handle(
        title: 'Plant Trees',
        description: 'We plant trees!',
        goalAmount: Money::fromString('5000.00'),
        ownerId: 1
    );

    expect($campaign->id)->toBe(1)
        ->and($campaign->title)->toBe('Plant Trees')
        ->and($campaign->description)->toBe('We plant trees!')
        ->and($campaign->goalAmount->toString())->toBe('5000.00')
        ->and($campaign->status)->toBe(CampaignStatus::OPEN);
});
