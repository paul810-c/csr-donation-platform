<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Employee\Employee;
use App\Domain\Employee\EmployeeRepositoryInterface;
use App\Models\Employee as EloquentEmployee;
use Illuminate\Support\Facades\Hash;

final class EloquentEmployeeRepository implements EmployeeRepositoryInterface
{
    public function findByEmail(string $email): ?Employee
    {
        $model = EloquentEmployee::where('email', $email)->first();

        return $model
            ? new Employee($model->id, $model->name, $model->email)
            : null;
    }

    public function checkCredentials(string $email, string $plainPassword): bool
    {
        $model = EloquentEmployee::where('email', $email)->first();

        return $model !== null && Hash::check($plainPassword, $model->password);
    }

    public function generateToken(Employee $employee): string
    {
        $model = EloquentEmployee::find($employee->id);

        if ($model === null) {
            throw new \RuntimeException('Employee not found');
        }

        return $model->createToken('auth_token')->plainTextToken;
    }
}
