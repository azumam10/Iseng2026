<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Atlet;
use App\Models\Pelatih;

class JadwalLatihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'atlet_id',
        'tanggal',
        'durasi',
        'jenis_latihan',
        'pelatih_id',
        'catatan',
    ];

    public function atlet()
    {
        return $this->belongsTo(Atlet::class);
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }
}
