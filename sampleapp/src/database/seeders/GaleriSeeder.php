<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('galeris')->insert([
            [
                'title' => 'Latihan Pagi',
                'image' => 'latihan jpg',
                'caption' => 'Latihan rutin pagi hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
