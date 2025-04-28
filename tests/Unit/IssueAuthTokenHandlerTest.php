<?php

declare(strict_types=1);

use App\Application\UseCases\IssueAuthToken\IssueAuthTokenHandler;
use App\Domain\Employee\Employee;
use App\Domain\Employee\EmployeeRepositoryInterface;
use Mockery\MockInterface;

// Define test variables
/** @var EmployeeRepositoryInterface&MockInterface $repository */
$repository = Mockery::mock(EmployeeRepositoryInterface::class);
/** @var IssueAuthTokenHandler $handler */
$handler = new IssueAuthTokenHandler($repository);

beforeEach(function () use (&$repository, &$handler) {
    $repository = Mockery::mock(EmployeeRepositoryInterface::class);
    $handler = new IssueAuthTokenHandler($repository);
});

test('it issues a token for valid credentials', function () use (&$repository, &$handler) {
    $email = 'employee@example.com';
    $password = 'password123';
    $employee = new Employee(1, 'John Doe', $email);
    $expectedToken = 'fake-token';

    $repository->expects('checkCredentials')
        ->with($email, $password)
        ->andReturnTrue();

    $repository->expects('findByEmail')
        ->with($email)
        ->andReturns($employee);

    $repository->expects('generateToken')
        ->with($employee)
        ->andReturns($expectedToken);

    $token = $handler->handle($email, $password);

    expect($token)->toBe($expectedToken);
});

test('it throws InvalidArgumentException when credentials are invalid', function () use (&$repository, &$handler) {
    $email = 'employee@example.com';
    $password = 'wrong-password';

    $repository->expects('checkCredentials')
        ->with($email, $password)
        ->andReturnFalse();

    expect(fn () => $handler->handle($email, $password))
        ->toThrow(InvalidArgumentException::class, 'Invalid credentials.');
});
