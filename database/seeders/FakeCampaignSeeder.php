<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class FakeCampaignSeeder extends Seeder
{
    public function run(): void
    {
        Campaign::factory()->count(5)->create();
    }
}
