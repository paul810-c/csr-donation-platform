<?php

declare(strict_types=1);

use App\Application\UseCases\ListCampaigns\ListCampaignsHandler;
use App\Domain\Campaign\Campaign;
use App\Domain\Campaign\CampaignRepositoryInterface;
use App\Domain\Common\ValueObject\Money;
use App\Domain\Campaign\CampaignStatus;
use Mockery\MockInterface;

// Mocks
/** @var CampaignRepositoryInterface&MockInterface $repository */
$repository = Mockery::mock(CampaignRepositoryInterface::class);
/** @var ListCampaignsHandler $handler */
$handler = new ListCampaignsHandler($repository);

beforeEach(function () use (&$repository, &$handler) {
    $repository = Mockery::mock(CampaignRepositoryInterface::class);
    $handler = new ListCampaignsHandler($repository);
});

test('it lists all campaigns for an owner', function () use (&$repository, &$handler) {
    $campaign = new Campaign(
        id: 1,
        title: 'Bike More',
        description: 'Encourage biking!',
        goalAmount: Money::fromString('2000.00'),
        ownerId: 42,
        status: CampaignStatus::OPEN,
        createdAt: new DateTimeImmutable(),
        updatedAt: new DateTimeImmutable(),
    );

    $repository->expects('findAllByOwnerId')
        ->with(42)
        ->andReturn([$campaign]);

    $campaigns = $handler->handle(42);

    expect($campaigns)
        ->toBeArray()
        ->and(count($campaigns))->toBe(1)
        ->and($campaigns[0])->toBeInstanceOf(Campaign::class)
        ->and($campaigns[0]->title)->toBe('Bike More');
});

test('it returns an empty array when no campaigns exist for the owner', function () use (&$repository, &$handler) {
    $repository->expects('findAllByOwnerId')
        ->with(99)
        ->andReturn([]);

    $campaigns = $handler->handle(99);

    expect($campaigns)
        ->toBeArray()
        ->and($campaigns)->toBeEmpty();
});