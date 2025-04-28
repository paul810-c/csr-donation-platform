<?php

use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('lists all donations for a given campaign', function () {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create();

    $anotherEmployee = Employee::factory()->create();

    CampaignDonation::factory()->create([
        'campaign_id' => $campaign->id,
        'employee_id' => $employee->id,
        'amount_in_cents' => 7500,
    ]);

    CampaignDonation::factory()->create([
        'campaign_id' => $campaign->id,
        'employee_id' => $anotherEmployee->id,
        'amount_in_cents' => 10000,
    ]);

    Sanctum::actingAs($employee);

    $response = getJson("/api/campaigns/{$campaign->id}/donations");

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment([
            'employee_id' => $employee->id,
            'campaign_id' => $campaign->id,
            'amount' => '75.00',
        ])
        ->assertJsonFragment([
            'employee_id' => $anotherEmployee->id,
            'campaign_id' => $campaign->id,
            'amount' => '100.00',
        ]);
});

it('returns empty data for a non existing campaign', function () {
    $employee = Employee::factory()->create();
    Sanctum::actingAs($employee);

    $response = getJson('/api/campaigns/9999/donations');

    $response->assertOk();
    $response->assertJson([
        'data' => [],
    ]);
});
