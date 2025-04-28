<?php

namespace Database\Seeders;

use App\Models\CampaignDonation;
use Illuminate\Database\Seeder;

class FakeDonationSeeder extends Seeder
{
    public function run(): void
    {
        CampaignDonation::factory()->count(20)->create();
    }
}
