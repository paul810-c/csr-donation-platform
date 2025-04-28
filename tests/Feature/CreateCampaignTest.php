<?php

use App\Models\Employee;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

it('allows employee to create a campaign', function () {
    $employee = Employee::factory()->create([
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($employee);

    $payload = [
        'title' => 'Recycle',
        'description' => 'Recycle',
        'goal_amount' => '5000.00',
    ];

    $response = postJson('/api/campaigns', $payload);

    $response->assertCreated()
        ->assertJsonPath('title', $payload['title'])
        ->assertJsonPath('description', $payload['description'])
        ->assertJsonPath('goal_amount', $payload['goal_amount']);

    assertDatabaseHas('campaigns', [
        'title' => $payload['title'],
        'description' => $payload['description'],
    ]);
});

it('rejects creating a campaign without a title', function () {
    $employee = Employee::factory()->create();
    Sanctum::actingAs($employee);

    $payload = [
        'description' => 'Plant more',
        'goal_amount' => '1000.00',
    ];

    $response = postJson('/api/campaigns', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

it('rejects an unauthenticated employee from creating a campaign', function () {
    $payload = [
        'title' => 'Bike more',
        'description' => 'Bike more',
        'goal_amount' => '3000.00',
    ];

    $response = postJson('/api/campaigns', $payload);

    $response->assertUnauthorized();
});
