<?php

namespace Database\Seeders;

use App\Models\JadwalLatihan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalLatihanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal_latihans')->insert([
            [
                'atlet_id' => 1,
                'pelatih_id' => 1,
                'tanggal' => now(),
                'durasi' => 90,
                'jenis_latihan' => 'Fisik',
                'catatan' => 'Latihan kekuatan otot',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'atlet_id' => 2,
                'pelatih_id' => 2,
                'tanggal' => now()->addDay(),
                'durasi' => 60,
                'jenis_latihan' => 'Teknik',
                'catatan' => 'Fokus teknik dasar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Tambahkan data latihan lainnya sesuai kebutuhan
    }
}
