<?php

declare(strict_types=1);

use App\Application\UseCases\GetCampaignWithTotals\GetCampaignWithTotalsHandler;
use App\Domain\Campaign\Campaign;
use App\Domain\Campaign\CampaignRepositoryInterface;
use App\Domain\Common\ValueObject\Money;
use App\Domain\Donation\DonationRepositoryInterface;
use App\Domain\Campaign\CampaignStatus;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;

/** @var CampaignRepositoryInterface&MockInterface $campaigns */
$campaigns = Mockery::mock(CampaignRepositoryInterface::class);
/** @var DonationRepositoryInterface&MockInterface $donations */
$donations = Mockery::mock(DonationRepositoryInterface::class);
/** @var LoggerInterface&MockInterface $logger */
$logger = Mockery::mock(LoggerInterface::class);
/** @var GetCampaignWithTotalsHandler $handler */
$handler = new GetCampaignWithTotalsHandler($campaigns, $donations, $logger);

beforeEach(function () use (&$campaigns, &$donations, &$logger, &$handler) {
    $campaigns = Mockery::mock(CampaignRepositoryInterface::class);
    $donations = Mockery::mock(DonationRepositoryInterface::class);
    $logger = Mockery::mock(LoggerInterface::class);
    $handler = new GetCampaignWithTotalsHandler($campaigns, $donations, $logger);
});

test('it returns a campaign with total raised amount', function () use (&$campaigns, &$donations, &$handler) {
    $campaign = new Campaign(
        id: 1,
        title: 'Recycle',
        description: 'Recycle more!',
        goalAmount: Money::fromString('1000.00'),
        ownerId: 1,
        status: CampaignStatus::OPEN,
        createdAt: new DateTimeImmutable(),
        updatedAt: new DateTimeImmutable(),
    );

    $campaigns->expects('findById')
        ->with(1)
        ->andReturn($campaign);

    $donations->expects('getTotalDonatedToCampaign')
        ->with(1)
        ->andReturn(500);

    $result = $handler->handle(1);

    expect($result)->toMatchArray([
        'id' => 1,
        'title' => 'Recycle',
        'description' => 'Recycle more!',
        'goal_amount' => '1000.00',
        'raised_amount' => '5.00',
        'status' => 'open',
        'owner_id' => 1,
        'created_at' => $campaign->createdAt->format(DATE_ATOM),
        'updated_at' => $campaign->updatedAt->format(DATE_ATOM),
    ]);
});

test('it returns null and logs when campaign not found', function () use (&$campaigns, &$logger, &$handler) {
    $campaigns->expects('findById')
        ->with(999)
        ->andReturn(null);

    $logger->expects('warning')
        ->with('Campaign not found', Mockery::subset(['campaign_id' => 999]));

    $result = $handler->handle(999);

    expect($result)->toBeNull();
});

