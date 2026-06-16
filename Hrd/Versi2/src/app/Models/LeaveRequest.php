<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LeaveRequest extends Model
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
        'document',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $appends = ['days_used'];

    // ─── RELATIONS ────────────────────────────────────────────────

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

    public function hrdApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hrd_approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // ─── SCOPES ──────────────────────────────────────────────────

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

    // ─── ACCESSORS ────────────────────────────────────────────────

    /**
     * Hitung jumlah hari kerja (Senin–Jumat) yang digunakan pada request ini.
     */
    public function getDaysUsedAttribute(): int
    {
        if (! $this->start_date || ! $this->end_date) {
            return 0;
        }

        return CarbonPeriod::create($this->start_date, $this->end_date)
            ->filter('isWeekday')
            ->count();
    }

    // ─── HOOKS ───────────────────────────────────────────────────

    protected static function booted(): void
    {
        self::creating(function (self $model) {
            if (is_null($model->requested_by_user_id) && auth()->check()) {
                $model->requested_by_user_id = auth()->id();
            }
        });
    }
}
