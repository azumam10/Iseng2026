<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'usia',
        'alamat',
        'jenis_kelamin',
        'kategori',
        'tinggi',
        'berat_badan',
        'riwayat_cedera',
        'status_kesehatan',
        'foto',
    ];

    public function jadwalLatihan()
    {
        return $this->hasMany(JadwalLatihan::class);
    }
}
