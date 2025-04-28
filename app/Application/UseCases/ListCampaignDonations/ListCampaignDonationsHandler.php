<?php

declare(strict_types=1);

namespace App\Application\UseCases\ListCampaignDonations;

use App\Domain\Donation\Donation;
use App\Domain\Donation\DonationRepositoryInterface;

final readonly class ListCampaignDonationsHandler
{
    public function __construct(
        private DonationRepositoryInterface $repository
    ) {}

    /**
     * @return Donation[]
     */
    public function handle(int $campaignId): array
    {
        return $this->repository->findAllByCampaignId($campaignId);
    }
}
