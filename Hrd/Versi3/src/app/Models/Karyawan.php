<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =[
        'nip',
        'nama',
        'gender',
        'dapartement_id',
        'jabatan_id',
        'alamat',
        'tanggal_lahir',
        'generasi',
        'tanggal_masuk',
        'tanggal_masuk',
        'pendidikan',
        'quota_cuti'
    ];

    public function dapartement(){
        return $this->belongsTo(Dapartement::class);
    }

    public function jabatan(){
        return $this->belongsTo(Jabatan::class);
    }

    public function cuti(){
        return $this->belongsTo(Cuti::class);
    }

}
