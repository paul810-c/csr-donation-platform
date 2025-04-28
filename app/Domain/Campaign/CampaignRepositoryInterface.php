<?php

declare(strict_types=1);

namespace App\Domain\Campaign;

interface CampaignRepositoryInterface
{
    public function save(Campaign $campaign): Campaign;

    public function findById(int $id): ?Campaign;

    /** @return Campaign[] */
    public function findAllByOwnerId(int $ownerId): array;
}
