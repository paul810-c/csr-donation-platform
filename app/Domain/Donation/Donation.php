<?php

declare(strict_types=1);

namespace App\Domain\Donation;

use App\Domain\Common\ValueObject\Money;

readonly class Donation
{
    public function __construct(
        public ?int $id,
        public int $campaignId,
        public int $employeeId,
        public Money $amount,
        public \DateTimeImmutable $createdAt,
    ) {}
}
