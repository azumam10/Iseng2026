<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie agar tidak stale
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'super_admin',
            'hrd',
            'kepala_bagian',
            'employee',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name'       => $role,
                'guard_name' => 'web',
            ]);
        }

        // Assign super_admin ke user admin
        $admin = User::where('email', 'admin@admin.com')->first();

        if ($admin) {
            $admin->assignRole('super_admin');
        }
    }
}