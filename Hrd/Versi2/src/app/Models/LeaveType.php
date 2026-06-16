<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class LeaveType extends Model
{
    protected $fillable = ['name', 'description', 'quota_per_year'];

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
