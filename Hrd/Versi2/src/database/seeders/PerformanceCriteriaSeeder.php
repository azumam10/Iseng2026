<?php

namespace Database\Seeders;

use App\Models\PerformanceCriteria;
use Illuminate\Database\Seeder;

class PerformanceCriteriaSeeder extends Seeder
{
    /**
     * Contoh kriteria dengan total bobot = 100.
     * Sesuaikan dengan kebutuhan perusahaan.
     */
    public function run(): void
    {
        $criteria = [
            [
                'name'        => 'Kedisiplinan',
                'weight'      => 25.00,
                'description' => 'Ketepatan waktu, kehadiran, dan kepatuhan terhadap aturan perusahaan.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Kualitas Kerja',
                'weight'      => 30.00,
                'description' => 'Akurasi, ketelitian, dan standar hasil pekerjaan.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Kerjasama Tim',
                'weight'      => 20.00,
                'description' => 'Kemampuan bekerja dalam tim dan komunikasi antar rekan.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Inisiatif',
                'weight'      => 15.00,
                'description' => 'Kemampuan mengambil inisiatif dan memberikan ide konstruktif.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Tanggung Jawab',
                'weight'      => 10.00,
                'description' => 'Kesediaan bertanggung jawab atas tugas dan keputusan.',
                'is_active'   => true,
            ],
        ];

        foreach ($criteria as $item) {
            PerformanceCriteria::firstOrCreate(
                ['name' => $item['name']],
                $item,
            );
        }
    }
}