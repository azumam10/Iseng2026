<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = ['name', 'description', 'quota_per_year'];

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}