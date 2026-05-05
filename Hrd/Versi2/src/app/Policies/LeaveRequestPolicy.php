<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
    }

    public function viewAny($user): bool
    {
        return $user->hasAnyRole(['hrd', 'kepala_bagian', 'employee']);
    }

    public function view($user, LeaveRequest $leaveRequest): bool
    {
        return true;
    }

    public function create($user): bool
    {
        return $user->hasAnyRole(['hrd', 'kepala_bagian']);
    }

    public function update($user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasAnyRole(['hrd']);
    }

    public function delete($user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasRole('super_admin');
    }
}