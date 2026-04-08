<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeavePolicy
{

public function before(User $user, string $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
{
    return $user->hasRole('HRD') || $user->hasRole('Kepala Bagian');
}

public function view(User $user, Leave $leave)
{
    if ($user->hasRole('HRD')) return true;
    if ($user->hasRole('Kepala Bagian')) {
        // hanya bisa melihat cuti bawahannya
        return $leave->employee->department_id == $user->employee->department_id;
    }
    return false;
}

public function create(User $user)
{
    if ($user->hasRole('HRD')) return true;
    if ($user->hasRole('Kepala Bagian')) {
        // bisa membuat cuti untuk bawahannya
        return true; // tapi perlu dibatasi di form agar hanya memilih bawahan
    }
    return false;
}

public function update(User $user, Leave $leave)
{
    if ($user->hasRole('HRD')) return true;
    if ($user->hasRole('Kepala Bagian')) {
        // bisa update jika status masih pending dan itu milik bawahannya
        return $leave->status == 'pending' && $leave->employee->department_id == $user->employee->department_id;
    }
    return false;
}

public function approve(User $user, Leave $leave)
{
    return $user->hasRole('HRD');
}
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Leave $leave): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Leave $leave): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Leave $leave): bool
    {
        return false;
    }
}
