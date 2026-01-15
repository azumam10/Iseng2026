# Dokumentasi Aplikasi HRD Management

Aplikasi berbasis web untuk mempermudah pekerjaan HRD dalam manajemen pegawai.

## Arsitektur Sistem

- **Container**: Docker
- **Framework**: Laravel 12
- **Admin Panel**: Filament v3
- **OS**: WSL Linux Ubuntu

---

## Instalasi

### 1. Instalasi Laravel

```bash
composer create-project laravel/laravel . "12.*" --prefer-dist
```

**Setelah instalasi, jalankan perintah berikut:**

```bash
php artisan key:generate
```
> Untuk generate app key

```bash
php artisan storage:link
```
> Membuat folder storage untuk file seperti foto, dokumen, dan lainnya

### 2. Konfigurasi File .env

Sesuaikan isi file `.env` dengan konfigurasi Docker Anda:
- Database connection (DB_HOST, DB_NAME, DB_USER, DB_PASSWORD)
- Dan konfigurasi lainnya sesuai kebutuhan

### 3. Jalankan Migrasi Database

```bash
php artisan migrate
```
> Membuat tabel database

```bash
php artisan migrate:fresh
```
> Merancang ulang tabel dari awal jika ada perubahan

### 4. Instalasi Filament v3

```bash
composer require filament/filament:"^3.3" -W
```
> `-W` = update dependency dengan aman (wajib agar kompatibel dengan Laravel 12)

**Install panel admin:**

```bash
php artisan filament:install --panels
```
> Default nama panel adalah 'admin'

**Buat user admin pertama:**

```bash
php artisan make:filament-user
```
> Wajib untuk login ke admin panel

**Re-migrate jika diperlukan:**

```bash
php artisan migrate
```
> Filament akan membuat table sendiri

### 5. Pengaturan File & Permission

```bash
# Ganti ownership ke www-data (user PHP-FPM)
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Set permission standar Laravel (writable untuk group)
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
```

### 6. Akses Aplikasi

Buka browser dan akses: `http://localhost`

---

## Langkah Kerja / Development

### 1. Instalasi Spatie Laravel Permission

```bash
composer require spatie/laravel-permission
```

**Mengapa Spatie Permission?**
- Role-Based Access Control (RBAC) yang proper untuk data sensitif (gaji, cuti, KTP)
- Library paling populer di Laravel ecosystem (jutaan download, actively maintained)
- Mudah terintegrasi dengan Filament
- Support middleware, gates, dan policies untuk proteksi route/resource

**Setup Spatie:**

```bash
# Publish config & migration
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Jalankan migration
php artisan migrate
```

### 2. Setup Role & Permission Dasar

Buat file seeder baru untuk role dan permission:

```bash
php artisan make:seeder RolePermissionSeeder
```

**Isi file `database/seeders/RolePermissionSeeder.php`:**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        $admin->givePermissionTo(Permission::all());

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo(['approve-cuti', 'view-pegawai']);

        $employee = Role::create(['name' => 'karyawan']);
        $employee->givePermissionTo(['view-pegawai']);
    }
}
```

**Jalankan seeder:**

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### 3. Setup Model User dengan Spatie

Buka file `app/Models/User.php` dan tambahkan:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;
    
    // ... kode lainnya
}
```

**Assign role ke user admin:**

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
$user->assignRole('admin');
exit
```

### 4. Buat Migration & Model (Departemen, Jabatan, Pegawai)

```bash
php artisan make:model Departemen -ms
php artisan make:model Jabatan -ms
php artisan make:model Pegawai -ms
```

> `-ms` = create migration & seeder

**Sesuaikan migration file untuk masing-masing model sesuai kebutuhan, kemudian jalankan:**

```bash
php artisan migrate
```

Jika ada error, perbaiki migration lalu:

```bash
php artisan migrate:fresh
```

### 5. Buat CRUD dengan Filament Resource

```bash
php artisan make:filament-resource Pegawai --generate
php artisan make:filament-resource Departemen --generate
php artisan make:filament-resource Jabatan --generate
```

> Flag `--generate` akan membuat CRUD otomatis berdasarkan model dan migration

**Test aplikasi:**
Buka `http://localhost` dan akses admin panel

**Edit tampilan resource:**
Edit file di `app/filament/resources/` sesuai kebutuhan

---

## Perintah Utility

### Clear Cache & Config

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

> Gunakan perintah ini jika aplikasi tidak mendeteksi perubahan konfigurasi

---

## Struktur Folder Project

```
laravel-project/
├── app/
│   ├── Models/              (Model: Departemen, Jabatan, Pegawai, User)
│   ├── filament/
│   │   └── Resources/       (Filament resources untuk CRUD)
│   └── ...
├── database/
│   ├── migrations/          (Migration files)
│   └── seeders/             (Database seeders)
├── routes/
│   └── web.php              (Route definitions)
├── .env                     (Environment configuration)
├── docker-compose.yml       (Docker configuration)
└── ...
```

---

## Checklist Instalasi

- [ ] Laravel 12 terinstall
- [ ] APP_KEY di-generate
- [ ] File .env dikonfigurasi sesuai Docker
- [ ] Database migration selesai
- [ ] Filament v3 terinstall
- [ ] User admin pertama dibuat
- [ ] Spatie Permission terinstall
- [ ] Role & Permission seeder dijalankan
- [ ] Model User ditambah HasRoles trait
- [ ] Model Departemen, Jabatan, Pegawai dibuat
- [ ] Migration untuk semua model dijalankan
- [ ] Filament Resource untuk CRUD dibuat
- [ ] Aplikasi dapat diakses di `http://localhost`