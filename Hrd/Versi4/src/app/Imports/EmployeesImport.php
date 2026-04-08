<?php

namespace App\Imports;


use App\Models\Employee;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class EmployeesImport implements ToModel, WithHeadingRow, WithStartRow
{
    private $departments = [];

    public function __construct()
    {
        // inisialisasi department cache
        $this->departments = Department::pluck('id', 'name')->toArray();
    }

    public function startRow(): int
    {
        return 7; // mulai dari baris 7 (index 7)
    }

    public function model(array $row)
{
    $nik = $row['no_ktp'] ?? null;
    if (!$nik) return null;

    $bagian_text = $row['bagian'] ?? null;

    // handle department
    $department_id = null;
    if ($bagian_text) {
        $bagian_text = trim($bagian_text);
        if (!isset($this->departments[$bagian_text])) {
            $dept = Department::create(['name' => $bagian_text]);
            $this->departments[$bagian_text] = $dept->id;
        }
        $department_id = $this->departments[$bagian_text];
    }

    return new Employee([
        'nik' => $nik,
        'nama' => $row['nama'] ?? null,
        'no_ktp' => $row['no_ktp'] ?? null,
        'agama' => $row['agama'] ?? null,
        'status_karyawan' => $row['status'] ?? null,
        'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
        'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null),
        'pendidikan' => $row['pendidikan'] ?? null,
        'status_pernikahan' => $row['status_pernikahan'] ?? null,
        'tanggal_masuk' => $this->parseDate($row['masuk_bekerja'] ?? null),
        'awal_kontrak' => $this->parseDate($row['awal_kontrak'] ?? null),
        'akhir_kontrak' => $this->parseDate($row['akhir_kontrak'] ?? null),
        'alamat' => $row['alamat'] ?? null,
        'jabatan' => $row['jabatan'] ?? null,
        'bagian_text' => $bagian_text,
        'department_id' => $department_id,
    ]);
}

    private function parseDate($value)
    {
        if (!$value) return null;
        // Coba parse berbagai format
        try {
            // Jika berupa string tanggal dengan jam
            if (strpos($value, '00:00:00') !== false) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $value)->toDateString();
            }
            // Jika format dd/mm/yyyy
            if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $value)) {
                return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
            }
            // Jika format lain
            return Carbon::parse($value)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }
}