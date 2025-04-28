<?php

declare(strict_types=1);

namespace App\Domain\Employee;

interface EmployeeRepositoryInterface
{
    public function findByEmail(string $email): ?Employee;

    public function checkCredentials(string $email, string $plainPassword): bool;

    public function generateToken(Employee $employee): string;
}
