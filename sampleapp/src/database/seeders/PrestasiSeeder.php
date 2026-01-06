<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prestasis')->insert([
            [
                'atlet_id' => 1,
                'kejuaraan' => 'Kejuaraan Nasional',
                'kategori' => 'Senior',
                'medali' => 'Emas',
                'tanggal' => now()->subMonth(),
                'deskripsi' => 'Juara 1 nasional',
                'dokumentasi_foto' => 'emas.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
