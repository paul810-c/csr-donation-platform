<?php

declare(strict_types=1);

namespace App\Application\UseCases\ListEmployeeDonations;

use App\Domain\Donation\Donation;
use App\Domain\Donation\DonationRepositoryInterface;

final readonly class ListEmployeeDonationsHandler
{
    public function __construct(
        private DonationRepositoryInterface $repository
    ) {}

    /**
     * @return Donation[]
     */
    public function handle(int $employeeId): array
    {
        return $this->repository->findAllByEmployeeId($employeeId);
    }
}
