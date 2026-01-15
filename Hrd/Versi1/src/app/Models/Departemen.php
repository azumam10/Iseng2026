<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    protected $fillable = ['nama', 'deskripsi']; 

    public function jabatans() {
    return $this->hasMany(Jabatan::class);
    }

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }
    
}

