<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    use HasFactory;

    protected $table = 'pelatih'; // Nama tabel di database

    protected $fillable = [
        'nama',
        'alamat',
        'jenis_kelamin',
        'spesialisasi',
        'foto',
    ];
}
