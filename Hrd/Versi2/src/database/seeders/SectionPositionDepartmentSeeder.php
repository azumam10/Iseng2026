<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use App\Models\Section;

class SectionPositionDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========================
        // 1. SEED DEPARTMENTS
        // ========================
        $department = Department::firstOrCreate(
            ['name' => 'Produksi'],        // kondisi pencarian
            ['code' => 'PROD']             // data tambahan jika belum ada
        );

        // ========================
        // 2. SEED POSITIONS
        // ========================
        $positions = [
            ['name' => 'Staff',           'level' => 'STAFF'],
            ['name' => 'Oprator',         'level' => 'OPERATOR'],   // Operator
            ['name' => 'Engenering',      'level' => 'STAFF'],      // Engineering
            ['name' => 'Direktur Utama',  'level' => null],         // level khusus
            ['name' => 'HRGA',            'level' => 'STAFF'],
            ['name' => 'PURC',            'level' => 'STAFF'],      // Purchasing
            ['name' => 'Acounting',       'level' => 'STAFF'],      // Accounting
            ['name' => 'Kepala Bagian',   'level' => 'MANAGER'],
            ['name' => 'PPIC_Exim',       'level' => 'STAFF'],
        ];

        foreach ($positions as $pos) {
            Position::firstOrCreate(
                ['name' => $pos['name']],          // cek berdasarkan nama
                ['level' => $pos['level']]         // set level jika baru
            );
        }

        // ========================
        // 3. SEED SECTIONS
        // ========================
        $sectionNames = [
            'Staff',
            'Security',
            'P6(DISPO CUFF.ID Band )',
            'P5(PREPSPONGE)',
            'P4(MACHINING)',
            'P2(METER,SETTING,NAIKI )',
            'P1(sewing,QC sewing)',
            'Manager Produksi',
            'Maintenance',
            'KEPALA BAGIAN',
            'GUDANG',
            'IQC',
            'OQC',
            'Engenering',
            'Driver',
            'Direktur Utama',
        ];

        foreach ($sectionNames as $sectionName) {
            Section::firstOrCreate(
                [
                    'name'          => $sectionName,
                    'department_id' => $department->id,   // semua milik Produksi
                ]
            );
        }
    }
}