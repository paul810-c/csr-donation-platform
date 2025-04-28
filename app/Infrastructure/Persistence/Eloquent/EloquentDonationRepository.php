<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Common\ValueObject\Money;
use App\Domain\Donation\Donation;
use App\Domain\Donation\DonationRepositoryInterface;
use App\Models\CampaignDonation as EloquentDonation;

final class EloquentDonationRepository implements DonationRepositoryInterface
{
    public function save(Donation $donation): Donation
    {
        $model = EloquentDonation::create([
            'campaign_id' => $donation->campaignId,
            'employee_id' => $donation->employeeId,
            'amount_in_cents' => $donation->amount->getAmountInCents(),
            'created_at' => $donation->createdAt->format('Y-m-d H:i:s'),
        ]);

        return new Donation(
            id: $model->id,
            campaignId: $model->campaign_id,
            employeeId: $model->employee_id,
            amount: Money::fromCents($model->amount_in_cents),
            createdAt: new \DateTimeImmutable($model->created_at),
        );
    }

    public function findAllByCampaignId(int $campaignId): array
    {
        return EloquentDonation::where('campaign_id', $campaignId)
            ->get()
            ->map(fn ($model) => new Donation(
                id: $model->id,
                campaignId: $model->campaign_id,
                employeeId: $model->employee_id,
                amount: Money::fromCents($model->amount_in_cents),
                createdAt: new \DateTimeImmutable($model->created_at),
            ))
            ->all();
    }

    public function findAllByEmployeeId(int $employeeId): array
    {
        return EloquentDonation::where('employee_id', $employeeId)
            ->get()
            ->map(fn ($model) => new Donation(
                id: $model->id,
                campaignId: $model->campaign_id,
                employeeId: $model->employee_id,
                amount: Money::fromCents($model->amount_in_cents),
                createdAt: new \DateTimeImmutable($model->created_at),
            ))
            ->all();
    }

    public function getTotalDonatedToCampaign(int $campaignId): int
    {
        return (int) EloquentDonation::where('campaign_id', $campaignId)->sum('amount_in_cents');
    }
}
