<?php

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        LeaveType::insert([
            ['name' => 'Sakit', 'requires_document' => true],
            ['name' => 'Pernikahan', 'requires_document' => true],
            ['name' => 'Melahirkan', 'requires_document' => true],
            ['name' => 'Izin', 'requires_document' => false],
        ]);
    }
}