<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CampaignDonation>
 */
class CampaignDonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'employee_id' => Employee::factory(),
            'amount_in_cents' => random_int(500, 10000),
            'created_at' => now(),
        ];
    }
}
