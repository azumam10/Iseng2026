<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;
    // protected $table = 'prestasi';//overide nama tabel
    protected $fillable = [
        'atlet_id',
        'kejuaraan', 
        'kategori', 
        'medali', 
        'tanggal', 
        'deskripsi', 
        'dokumentasi_foto', 
    ];

    public function atlet(){
        return $this->belongsTo(Atlet::class);
    }
}
