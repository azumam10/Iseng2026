<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        Jabatan::insert([
            [
                'kode_jabatan' => 'P005',
                'nama' => 'Staff',
                'gaji_pokok_min' => 200000.00,
                'gaji_pokok_max' => 250000.00,
                'deskripsi' => 'Staff operasional'
            ],
            [
                'kode_jabatan' => 'P002',
                'nama' => 'Kepala Oprasional',
                'gaji_pokok_min' => 2000000.00,
                'gaji_pokok_max' => 2500000.00,
                'deskripsi' => 'Kepala operasional'
            ],
            [
                'kode_jabatan' => 'P001',
                'nama' => 'Manajer Oprasional',
                'gaji_pokok_min' => 25000000.00,
                'gaji_pokok_max' => 25300000.00,
                'deskripsi' => 'Manajer operasional'
            ],
            
        ]);
    }
}
