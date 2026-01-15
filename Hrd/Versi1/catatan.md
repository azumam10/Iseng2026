aplikasi berbasis web ini di buat untuk mempermudah pekerjaan hrd dalam manajement pegawai.
arsitektur :
1. menggunakan Docker sebagai container projek
2. menggunakan framework laravel 12 
3. menggunakan filament v3 untuk admin panel
4. os menggunakan wsl linux ubuntu

=====================================================
* LANGKAH INSTALASI
1. INSTALASI LARAVEL
composer create-project laravel/laravel . "12.*" --prefer-dist
php artisan key:generate = untuk generate app key
php artisan storage:link = buat storage untuk file seperti foto,dokumen dan lainnya
2. MERUBAH ISI .ENV 
isi didalam .env di sesaikan dengn yang ada di dalam docker seperti DBconection dll
3. JALANKAN MIGRASI
php artisan migrate = perintah untuk migrasi table atau membuat table.
php artisan migrate:fresh = untuk merancang ulang table dari awal jika ada perubahan
4. INSTALASI FILAMENT V3
composer require filament/filament:"^3.3" -W   # -W = update dependency dengan aman (wajib biar kompatibel Laravel 12).
php artisan filament:install --panels  # Install panel admin (default nama 'admin')
php artisan make:filament-user  # Buat user admin pertama (wajib buat login)
php artisan migrate   # Re-migrate kalau perlu (Filament bikin table sendiri)

5. udah ikutin aja
# Ganti ownership ke www-data (user PHP-FPM)
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Permission standar Laravel (writable buat group)
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
-----------------------------------------------------
bisa juga cuman ini : 

LALU BUKA LOCALHOST DI BROWSER

-----------------------------------------------
untuk clear chace 
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
-----------------------------------------------
=========================================================
* LANGKAH KERJA
1. Install Spatie Laravel Permission
-------------------------------------------------- 
composer require spatie/laravel-permission
Ini wajib buat app HRD proper, karena data sensitif (gaji, cuti, KTP) harus punya role-based access control (RBAC). Spatie Permission adalah library terbaik & paling populer di Laravel ecosystem (jutaan download, maintained aktif).

# Publish config & migration
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Jalankan migration (bikin table roles, permissions, model_has_roles, dll)
php artisan migrate

Kenapa Spatie?
Mudah integrate dengan Filament (ada plugin resmi filament-spatie-roles-permissions).
Support middleware, gates, policies — aman buat protect route/resource.
--------------------------------------------------

2. Setup Role & Permission Dasar
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

php artisan db:seed --class=RolePermissionSeeder
------------------------------------------------------------------------

3. Buat akun untuk login pake tinker dulu
php artisan tinker
$user = App\Models\User::first();  // User admin lu yang baru dibuat
$user->assignRole('admin');
exit

di Model user jangan lupa tambahin
use Spatie\Permission\Traits\HasRoles;
use HasRoles;
----------------------------------------------------------------

4. BUAT MIGRATION SAMA MODEL BACH PERTAMA (Dapartemen,jabatan,pegawai)
php artisan make:model Departemen -ms  
php artisan make:model Jabatan -ms
php artisan make:model Pegawai -ms
lu sesuain masing masing isi migration sama modelnya apa aja
kalo udah sesuai semua jalanin migrasi table
php artisan migrate
kalo lu ada eror di migrasi benerin terus php artisan migrate:fresh 
----------------------------------------------------------------

5. BUAT CRUD  pake filament resouce
php artisan make:filament-resource Pegawai --generate
php artisan make:filament-resource Dapartemen --generate
php artisan make:filament-resource Jabatan --generate

-- generate fungsinya biar langsung kebuat crud berdasarkan data yang ada di migration ama modelnya
terus tes buka localhost
kalo lu mau edit tampilan dashboard
mainin di file resource nya app/filament/resources/resouceyang mau lo edit
--------------------------------------------------------------------------
