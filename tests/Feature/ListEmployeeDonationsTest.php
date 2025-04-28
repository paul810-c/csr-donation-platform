<?php

use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('lists only authenticated employee donations', function () {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create();

    $employee2 = Employee::factory()->create();
    $campaign2 = Campaign::factory()->create();

    CampaignDonation::factory()->create([
        'campaign_id' => $campaign->id,
        'employee_id' => $employee->id,
        'amount_in_cents' => 5000,
    ]);

    CampaignDonation::factory()->create([
        'campaign_id' => $campaign2->id,
        'employee_id' => $employee2->id,
        'amount_in_cents' => 8000,
    ]);

    Sanctum::actingAs($employee);

    $response = getJson('/api/donations');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'campaign_id' => $campaign->id,
            'employee_id' => $employee->id,
            'amount' => '50.00',
        ]);
});

it('rejects unauthenticated access to employee donations', function () {
    $response = getJson('/api/donations');

    $response->assertUnauthorized();
});
