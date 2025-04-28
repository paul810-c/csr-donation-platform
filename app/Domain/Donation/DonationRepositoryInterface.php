<?php

namespace App\Domain\Donation;

interface DonationRepositoryInterface
{
    public function save(Donation $donation): Donation;

    /** @return Donation[] */
    public function findAllByCampaignId(int $campaignId): array;

    /** @return Donation[] */
    public function findAllByEmployeeId(int $employeeId): array;

    public function getTotalDonatedToCampaign(int $campaignId): int;
}
