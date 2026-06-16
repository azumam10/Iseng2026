<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Position extends Model
{
    protected $fillable = ['name', 'level'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
