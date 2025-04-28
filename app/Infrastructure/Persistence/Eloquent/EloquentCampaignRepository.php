<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Campaign\Campaign;
use App\Domain\Campaign\CampaignRepositoryInterface;
use App\Domain\Campaign\CampaignStatus;
use App\Domain\Common\ValueObject\Money;
use App\Models\Campaign as EloquentCampaign;

final class EloquentCampaignRepository implements CampaignRepositoryInterface
{
    public function save(Campaign $campaign): Campaign
    {
        $model = $campaign->id
            ? EloquentCampaign::findOrFail($campaign->id)
            : new EloquentCampaign;

        $model->title = $campaign->title;
        $model->description = $campaign->description;
        $model->goal_amount_in_cents = $campaign->goalAmount->getAmountInCents();
        $model->owner_id = $campaign->ownerId;
        $model->status = $campaign->status->value;
        $model->save();

        return new Campaign(
            id: $model->id,
            title: $model->title,
            description: $model->description,
            goalAmount: Money::fromCents($model->goal_amount_in_cents), // updated
            ownerId: $model->owner_id,
            status: CampaignStatus::from($model->status),
            createdAt: $model->created_at
                ? new \DateTimeImmutable($model->created_at->format('Y-m-d H:i:s'))
                : new \DateTimeImmutable,
            updatedAt: $model->updated_at
                ? new \DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s'))
                : new \DateTimeImmutable,
        );
    }

    public function findById(int $id): ?Campaign
    {
        $model = EloquentCampaign::find($id);

        if (! $model) {
            return null;
        }

        return new Campaign(
            id: $model->id,
            title: $model->title,
            description: $model->description,
            goalAmount: Money::fromCents($model->goal_amount_in_cents),
            ownerId: $model->owner_id,
            status: CampaignStatus::from($model->status),
            createdAt: $model->created_at
                ? new \DateTimeImmutable($model->created_at->format('Y-m-d H:i:s'))
                : new \DateTimeImmutable,
            updatedAt: $model->updated_at
                ? new \DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s'))
                : new \DateTimeImmutable,
        );
    }

    public function findAllByOwnerId(int $ownerId): array
    {
        return EloquentCampaign::where('owner_id', $ownerId)
            ->get()
            ->map(fn (EloquentCampaign $model) => new Campaign(
                id: $model->id,
                title: $model->title,
                description: $model->description,
                goalAmount: Money::fromCents($model->goal_amount_in_cents),
                ownerId: $model->owner_id,
                status: CampaignStatus::from($model->status),
                createdAt: $model->created_at
                ? new \DateTimeImmutable($model->created_at->format('Y-m-d H:i:s'))
                : new \DateTimeImmutable,
                updatedAt: $model->updated_at
                    ? new \DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s'))
                    : new \DateTimeImmutable,
            ))
            ->all();
    }
}
