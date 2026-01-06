<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalPertandinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('jadwal_pertandingans')->insert([
            [
                'atlet_id' => 1,
                'tanggal' => now()->addWeek(),
                'lokasi' => 'GOR Senayan',
                'status' => 'terjadwal',
                'hasil' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
