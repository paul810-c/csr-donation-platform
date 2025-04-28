<?php

namespace App\Providers;

use App\Domain\Donation\Events\DonationCreated;
use App\Infrastructure\Listeners\SendDonationConfirmationListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(
            DonationCreated::class,
            [SendDonationConfirmationListener::class, 'handle']
        );
    }
}
