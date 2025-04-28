<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function authenticatedEmployee(): Employee
    {
        /** @var Employee|null $employee */
        $employee = Auth::user();

        if (! $employee) {
            abort(401, 'Unauthorized');
        }

        return $employee;
    }
}
