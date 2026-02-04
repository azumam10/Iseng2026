<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'department_id',
        'gaji_pokok_min',
        'gaji_pokok_max',
        'deskripsi',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
