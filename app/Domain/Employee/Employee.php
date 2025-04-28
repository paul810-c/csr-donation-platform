<?php

declare(strict_types=1);

namespace App\Domain\Employee;

readonly class Employee
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    ) {}
}
