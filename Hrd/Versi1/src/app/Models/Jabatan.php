<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $fillable = ['departemen_id', 'kode_jabatan', 'nama', 'gaji_pokok_min', 'gaji_pokok_max', 'deskripsi'];

    public function departemen() {
        return $this->belongsTo(Departemen::class);
        }
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }
}
