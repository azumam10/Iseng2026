<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
    'employee_id',
    'leave_type_id',
    'requested_by_user_id',
    'hrd_approved_by',
    'rejected_by',
    'start_date',
    'end_date',
    'reason',
    'notes',
    'status',
];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // RELATION
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function hrdApprovedBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'hrd_approved_by');
}
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // 🔥 SIMPLIFIED SCOPES
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

   
    protected static function booted()
{
    static::creating(function ($model) {
        if (is_null($model->requested_by_user_id) && auth()->check()) {
            $model->requested_by_user_id = auth()->id();
        }
    });
}
}