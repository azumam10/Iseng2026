<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Performance;

use App\Policies\EmployeePolicy;
use App\Policies\LeavePolicy;
use App\Policies\PerformancePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Employee::class => EmployeePolicy::class,
        Leave::class => LeavePolicy::class,
        Performance::class => PerformancePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}