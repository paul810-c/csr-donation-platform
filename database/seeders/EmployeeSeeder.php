<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'name' => 'Paul Schiller',
            'email' => 'paul@acme.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
