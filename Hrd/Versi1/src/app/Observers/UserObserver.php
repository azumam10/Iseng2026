<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Pegawai;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
{
    // assign role
    $user->assignRole('karyawan');

    // buat data pegawai otomatis
    Pegawai::create([
        'user_id' => $user->id,
        'nama_lengkap' => $user->name,
        'email' => $user->email,
        'status' => 'aktif',
    ]);
}

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
