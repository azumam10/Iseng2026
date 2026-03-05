<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'code'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}