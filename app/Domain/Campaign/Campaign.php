<?php

declare(strict_types=1);

namespace App\Domain\Campaign;

use App\Domain\Common\ValueObject\Money;

readonly class Campaign
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public Money $goalAmount,
        public int $ownerId,
        public CampaignStatus $status,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {}
}
