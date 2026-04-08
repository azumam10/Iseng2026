<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run()
{
    // Buat user HRD
    $hrd = User::create([
        'name' => 'HRD',
        'email' => 'hrd@example.com',
        'password' => bcrypt('password'),
    ]);
    $hrd->assignRole('HRD');

    // Buat user untuk setiap kepala bagian
    $kepalaBagians = Employee::where('jabatan', 'like', '%Kepala bagian%')->get();
    foreach ($kepalaBagians as $employee) {
        $user = User::create([
            'name' => $employee->nama,
            'email' => $employee->nik . '@example.com', // sesuaikan jika perlu
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('Kepala Bagian');
        $employee->user_id = $user->id;
        $employee->save();
    }
}
}
