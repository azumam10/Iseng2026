<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{

public function run()
{
    // Reset cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Daftar permission
    $permissions = [
        'view_employee', 'create_employee', 'update_employee', 'delete_employee',
        'view_leave', 'create_leave', 'update_leave', 'delete_leave', 'approve_leave',
        'view_performance', 'create_performance', 'update_performance', 'delete_performance', 'approve_performance',
    ];

    foreach ($permissions as $perm) {
        Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
    }

    // Buat role
    $hrd = Role::firstOrCreate(['name' => 'HRD', 'guard_name' => 'web']);
    $kabag = Role::firstOrCreate(['name' => 'Kepala Bagian', 'guard_name' => 'web']);

    // Assign semua permission ke HRD
    $hrd->givePermissionTo(Permission::all());

    // Assign permission terbatas ke Kepala Bagian
    $kabag->givePermissionTo([
        'view_employee',
        'create_leave', 'view_leave', 'update_leave',
        'create_performance', 'view_performance', 'update_performance',
    ]);
}
}