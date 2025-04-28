<?php

declare(strict_types=1);

namespace App\Infrastructure\Listeners;

use App\Domain\Donation\Events\DonationCreated;
use App\Infrastructure\Jobs\SendDonationConfirmationJob;

final class SendDonationConfirmationListener
{
    public function handle(DonationCreated $event): void
    {
        if ($event->donation->id === null) {
            return;
        }

        SendDonationConfirmationJob::dispatch($event->donation->id);
    }
}
