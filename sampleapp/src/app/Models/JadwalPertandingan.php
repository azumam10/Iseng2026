<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPertandingan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pertandingans'; // Nama tabel di database

    protected $fillable = [
        'atlet_id',
        'tanggal',
        'lokasi',
        'status',
        'hasil',
    ];

    // Relasi ke Atlet
    public function atlet()
    {
        return $this->belongsTo(Atlet::class);
    }
}
