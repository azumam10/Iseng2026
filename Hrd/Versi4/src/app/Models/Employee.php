<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Employee extends Model
{
    protected $fillable = [
        'nik', 'nama', 'no_ktp', 'agama', 'status_karyawan', 'jenis_kelamin',
        'tanggal_lahir', 'pendidikan', 'status_pernikahan', 'tanggal_masuk',
        'awal_kontrak', 'akhir_kontrak', 'alamat', 'jabatan', 'department_id',
        'bagian_text', 'kepala_bagian_id', 'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'awal_kontrak' => 'date',
        'akhir_kontrak' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function atasan()
    {
        return $this->belongsTo(self::class, 'kepala_bagian_id');
    }

    public function bawahan()
    {
        return $this->hasMany(self::class, 'kepala_bagian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }
}
