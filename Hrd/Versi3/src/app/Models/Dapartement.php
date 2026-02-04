<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function jabatans()
    {
        return $this->hasMany(Jabatan::class);
    }
}
