<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Department extends Model
{
    protected $fillable = ['name', 'code'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
