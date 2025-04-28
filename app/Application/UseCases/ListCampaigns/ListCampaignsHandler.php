<?php

declare(strict_types=1);

namespace App\Application\UseCases\ListCampaigns;

use App\Domain\Campaign\Campaign;
use App\Domain\Campaign\CampaignRepositoryInterface;

final readonly class ListCampaignsHandler
{
    public function __construct(
        private CampaignRepositoryInterface $repository
    ) {}

    /**
     * @return Campaign[]
     */
    public function handle(int $ownerId): array
    {
        return $this->repository->findAllByOwnerId($ownerId);
    }
}
