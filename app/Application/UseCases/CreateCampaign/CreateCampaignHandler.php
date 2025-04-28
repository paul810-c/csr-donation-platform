<?php

declare(strict_types=1);

namespace App\Application\UseCases\CreateCampaign;

use App\Domain\Campaign\Campaign;
use App\Domain\Campaign\CampaignRepositoryInterface;
use App\Domain\Campaign\CampaignStatus;
use App\Domain\Common\ValueObject\Money;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

final readonly class CreateCampaignHandler
{
    public function __construct(
        private CampaignRepositoryInterface $repository,
        private LoggerInterface $logger,
    ) {}

    public function handle(string $title, string $description, Money $goalAmount, int $ownerId): Campaign
    {
        $campaign = new Campaign(
            id: null,
            title: $title,
            description: $description,
            goalAmount: $goalAmount,
            ownerId: $ownerId,
            status: CampaignStatus::OPEN,
            createdAt: new DateTimeImmutable,
            updatedAt: new DateTimeImmutable,
        );

        $this->logger->info('Campaign created successfully', [
            'campaign_id' => $campaign->id,
            'owner_id' => $campaign->ownerId,
            'goal_amount' => $campaign->goalAmount->toString(),
        ]);

        return $this->repository->save($campaign);
    }
}
