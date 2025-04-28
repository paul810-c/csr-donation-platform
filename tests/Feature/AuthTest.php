<?php

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\postJson;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('Authentication', function () {
    it('authenticates an employee with correct credentials', function () {
        $employee = Employee::factory()->create([
            'email' => 'employee@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = postJson('/api/auth/token', [
            'email' => 'employee@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token']);
    });

    it('fails authentication with wrong password', function () {
        $employee = Employee::factory()->create([
            'email' => 'employee@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = postJson('/api/auth/token', [
            'email' => 'employee@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    });

    it('fails authentication with non existent email', function () {
        $response = postJson('/api/auth/token', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
    });

    it('fails authentication when email is missing', function () {
        $response = postJson('/api/auth/token', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    it('fails authentication when password is missing', function () {
        $response = postJson('/api/auth/token', [
            'email' => 'employee@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    });
});
