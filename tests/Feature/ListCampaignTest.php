<?php

use App\Models\Campaign;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('lists campaigns owned by authenticated employee', function () {
    $employee = Employee::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $otherEmployee = Employee::factory()->create();

    Campaign::factory()->count(2)->create([
        'owner_id' => $employee->id,
    ]);

    Campaign::factory()->count(1)->create([
        'owner_id' => $otherEmployee->id,
    ]);

    Sanctum::actingAs($employee);

    $response = getJson('/api/campaigns');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

it('returns 404 when fetching a non-existing campaign', function () {
    $employee = Employee::factory()->create();
    Sanctum::actingAs($employee);

    $response = getJson('/api/campaigns/9999');

    $response->assertNotFound();
});
