<?php

namespace Database\Seeders;

use App\Models\Atlet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtletSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('atlets')->insert([
            [
                'nama' => 'Budi Santoso',
                'usia' => 20,
                'alamat' => 'Jakarta',
                'jenis_kelamin' => 'Laki-laki',
                'kategori' => 'Senior',
                'tinggi' => 175,
                'berat_badan' => 68,
                'riwayat_cedera' => null,
                'status_kesehatan' => 'Sehat',
                'foto' => 'budi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Aminah',
                'usia' => 17,
                'alamat' => 'Bandung',
                'jenis_kelamin' => 'Perempuan',
                'kategori' => 'Junior',
                'tinggi' => 165,
                'berat_badan' => 55,
                'riwayat_cedera' => 'Cedera lutut ringan',
                'status_kesehatan' => 'Pemulihan',
                'foto' => 'siti.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
