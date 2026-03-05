<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_id', 'leave_type_id', 'start_date', 'end_date', 'reason',
        'status', 'kabag_approved_by', 'kabag_approved_at', 'hrd_approved_by',
        'hrd_approved_at', 'rejected_by', 'rejected_at', 'rejection_reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'kabag_approved_at' => 'datetime',
        'hrd_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function kabagApprover()
    {
        return $this->belongsTo(User::class, 'kabag_approved_by');
    }

    public function hrdApprover()
    {
        return $this->belongsTo(User::class, 'hrd_approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}