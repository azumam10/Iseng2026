<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip', 'nama_lengkap', 'gender', 'email', 'no_hp', 'alamat',
        'tanggal_lahir', 'generasi','tanggal_masuk', 'tanggal_keluar',
        'pendidikan', 'status', 'kinerja_score', 'kinerja',
        'foto', 'ktp', 'npwp', 'departemen_id', 'jabatan_id'
    ];

    protected $casts = [
        'tanggal_lahir'   => 'date:Y-m-d',
        'tanggal_masuk'   => 'date:Y-m-d',
        'tanggal_keluar'  => 'date:Y-m-d',
        'status'          => 'string',
    ];

    // Relasi
    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    // Accessor usia biar usia langsung generate dari tgl lhir
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir?->age;
    }

    // Scope contoh (nanti berguna di Filament filter)
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}