<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $fillable = [
        'employee_id', 'reviewer_id', 'review_date', 'period', 'score',
        'notes', 'status', 'approved_by', 'approved_at', 'rejection_reason'
    ];

    protected $casts = [
        'review_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}