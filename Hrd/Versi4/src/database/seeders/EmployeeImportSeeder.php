<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;

class EmployeeImportSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('app/imports/daftar_karyawan.xlsx');
        Excel::import(new EmployeesImport, $file);
    }
}