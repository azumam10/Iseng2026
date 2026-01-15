<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departemen;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        Departemen::insert([
            [
                'nama' => 'HRD',
                'deskripsi' => 'Human Resource Department'
            ],
            [
                'nama' => 'IT',
                'deskripsi' => 'Information Technology'
            ],
            [
                'nama' => 'Finance',
                'deskripsi' => 'Keuangan & Akuntansi'
            ],
        ]);
    }
}
