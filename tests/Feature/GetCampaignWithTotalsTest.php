<?php

use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\withHeaders;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('an employee can get a campaign with total raised amount', function () {
    $employee = Employee::factory()->create();
    $token = $employee->createToken('auth_token')->plainTextToken;

    $campaign = Campaign::factory()->create([
        'owner_id' => $employee->id,
        'goal_amount_in_cents' => 10000,
    ]);

    CampaignDonation::factory()->create([
        'campaign_id' => $campaign->id,
        'employee_id' => $employee->id,
        'amount_in_cents' => 2500,
    ]);

    CampaignDonation::factory()->create([
        'campaign_id' => $campaign->id,
        'employee_id' => $employee->id,
        'amount_in_cents' => 1500,
    ]);

    $response = withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson("/api/campaigns/{$campaign->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $campaign->id,
            'title' => $campaign->title,
            'description' => $campaign->description,
            'goal_amount' => '100.00',
            'raised_amount' => '40.00',
            'status' => $campaign->status,
            'owner_id' => $campaign->owner_id,
        ]);
});
