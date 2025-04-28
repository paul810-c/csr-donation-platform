<?php

namespace App\Providers;

use App\Domain\Campaign\CampaignRepositoryInterface;
use App\Domain\Donation\DonationRepositoryInterface;
use App\Domain\Employee\EmployeeRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\EloquentCampaignRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDonationRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            EmployeeRepositoryInterface::class,
            EloquentEmployeeRepository::class
        );

        $this->app->bind(
            CampaignRepositoryInterface::class,
            EloquentCampaignRepository::class
        );

        $this->app->bind(
            DonationRepositoryInterface::class,
            EloquentDonationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
