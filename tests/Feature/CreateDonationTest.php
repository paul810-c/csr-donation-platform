<?php

use App\Models\Campaign;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('allows authenticated employee to create a donation', function () {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create();

    Sanctum::actingAs($employee);

    $response = postJson('/api/donations', [
        'campaign_id' => $campaign->id,
        'amount' => '25.00',
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'id',
            'campaign_id',
            'employee_id',
            'amount',
            'created_at',
        ]);
});

it('rejects unauthenticated employee from creating a donation', function () {
    $campaign = Campaign::factory()->create();

    $response = postJson('/api/donations', [
        'campaign_id' => $campaign->id,
        'amount' => '25.00',
    ]);

    $response->assertUnauthorized();
});

it('validates required fields', function () {
    $employee = Employee::factory()->create();

    Sanctum::actingAs($employee);

    $response = postJson('/api/donations', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['campaign_id', 'amount']);
});

it('fails to donate to a non existing campaign', function () {
    $employee = Employee::factory()->create();

    Sanctum::actingAs($employee);

    $response = postJson('/api/donations', [
        'campaign_id' => 99999,
        'amount' => '50.00',
    ]);

    $response->assertStatus(422);
});
