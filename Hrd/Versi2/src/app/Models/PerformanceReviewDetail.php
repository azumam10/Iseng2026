<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReviewDetail extends Model
{
    protected $fillable = [
        'performance_review_id',
        'criteria_id',
        'score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    // ─── RELATIONS ────────────────────────────────────────────────

    public function review(): BelongsTo
    {
        return $this->belongsTo(PerformanceReview::class, 'performance_review_id');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(PerformanceCriteria::class, 'criteria_id');
    }
}