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
        'kabag_approved_by',
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

    // ==================== RELATIONSHIPS ====================
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function kabagApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kabag_approved_by');
    }

    public function hrdApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hrd_approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // ==================== SCOPES ====================
    public function scopePendingHead($query)
    {
        return $query->where('status', 'pending_head');
    }

    public function scopePendingHrd($query)
    {
        return $query->where('status', 'pending_hrd');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}