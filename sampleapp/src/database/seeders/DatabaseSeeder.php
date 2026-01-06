<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $this->callSeeders();
    }

    private function seedUsers(): void
    {
        if (! User::where('email', 'admin@admin.com')->exists()) {
            $users = User::factory()->createMany([
                [
                    'name' => 'admin',
                    'email' => 'admin@admin.com',
                    'password' => bcrypt('password'),
                ],
                [
                    'name' => 'pelatih',
                    'email' => 'pelatih@admin.com',
                    'password' => bcrypt('password'),
                ],
                [
                    'name' => 'atlet',
                    'email' => 'atlet@admin.com',
                    'password' => bcrypt('password'),
                ],
            ]);

            foreach ($users as $user) {
                if ($user->email == 'admin@admin.com') {
                    $user->assignRole('super_admin');
                }
            }
        }
    }

    private function callSeeders(): void
    {
        $this->call([
            
            AtletSeeder::class,
            GaleriSeeder::class,
            PelatihSeeder::class,
            JadwalLatihanSeeder::class,
            JadwalPertandinganSeeder::class,
            PrestasiSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
