<?php

declare(strict_types=1);

namespace App\Application\UseCases\GetCampaignWithTotals;

use App\Domain\Campaign\CampaignRepositoryInterface;
use App\Domain\Common\ValueObject\Money;
use App\Domain\Donation\DonationRepositoryInterface;
use Psr\Log\LoggerInterface;

final readonly class GetCampaignWithTotalsHandler
{
    public function __construct(
        private CampaignRepositoryInterface $campaigns,
        private DonationRepositoryInterface $donations,
        private LoggerInterface $logger,
    ) {}

    /** @return array<string, int|string|null> */
    public function handle(int $campaignId): ?array
    {
        $campaign = $this->campaigns->findById($campaignId);

        if (! $campaign) {
            $this->logger->warning('Campaign not found', [
                'campaign_id' => $campaignId,
            ]);

            return null;
        }

        $totalInCents = $this->donations->getTotalDonatedToCampaign($campaignId);
        $raised = Money::fromCents($totalInCents);

        return [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'description' => $campaign->description,
            'goal_amount' => $campaign->goalAmount->toString(),
            'raised_amount' => $raised->toString(),
            'status' => $campaign->status->value,
            'owner_id' => $campaign->ownerId,
            'created_at' => $campaign->createdAt->format(DATE_ATOM),
            'updated_at' => $campaign->updatedAt->format(DATE_ATOM),
        ];
    }
}
