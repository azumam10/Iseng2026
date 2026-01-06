<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelatihSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pelatih')->insert([
            [
                'nama' => 'Coach Andi',
                'alamat' => 'Surabaya',
                'jenis_kelamin' => 'Laki-laki',
                'spesialisasi' => 'Fisik',
                'foto' => 'andi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Coach Rina',
                'alamat' => 'Yogyakarta',
                'jenis_kelamin' => 'Perempuan',
                'spesialisasi' => 'Teknik',
                'foto' => 'rina.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
