<?php

declare(strict_types=1);

namespace App\Application\UseCases\IssueAuthToken;

use App\Domain\Employee\EmployeeRepositoryInterface;

final readonly class IssueAuthTokenHandler
{
    public function __construct(
        private EmployeeRepositoryInterface $repository
    ) {}

    public function handle(string $email, string $password): string
    {
        if (! $this->repository->checkCredentials($email, $password)) {
            throw new \InvalidArgumentException('Invalid credentials.');
        }

        $employee = $this->repository->findByEmail($email);

        if (! $employee) {
            throw new \DomainException('Employee not found');
        }

        return $this->repository->generateToken($employee);
    }
}
