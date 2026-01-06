<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'super_admin',
            'admin',
            'pelatih',
            'atlet',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Optional: Create permissions and assign to roles
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_atlets',
            'manage_pelatihs',
            'manage_jadwal',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to super_admin
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->givePermissionTo(Permission::all());
    }
}
