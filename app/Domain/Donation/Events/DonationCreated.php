<?php

declare(strict_types=1);

namespace App\Domain\Donation\Events;

use App\Domain\Donation\Donation;

final readonly class DonationCreated
{
    public function __construct(
        public Donation $donation
    ) {}
}
