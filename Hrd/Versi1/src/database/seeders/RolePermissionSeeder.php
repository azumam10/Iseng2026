<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat permissions
        Permission::create(['name' => 'view-pegawai']);
        Permission::create(['name' => 'create-pegawai']);
        Permission::create(['name' => 'edit-pegawai']);
        Permission::create(['name' => 'delete-pegawai']);
        Permission::create(['name' => 'approve-cuti']);
        Permission::create(['name' => 'view-payslip']);

        // Buat roles & assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());  // Admin punya semua

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo(['approve-cuti', 'view-pegawai']);

        $employee = Role::create(['name' => 'karyawan']);
        $employee->givePermissionTo(['view-pegawai']);  // Cuma liat sendiri nanti
    }
}