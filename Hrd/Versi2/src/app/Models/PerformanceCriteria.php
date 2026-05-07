<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceCriteria extends Model
{
    protected $table = 'performance_criteria';

    protected $fillable = [
        'name',
        'weight',
        'description',
        'is_active',
    ];

    protected $casts = [
        'weight'    => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ─── RELATIONS ────────────────────────────────────────────────

    public function reviewDetails(): HasMany
    {
        return $this->hasMany(PerformanceReviewDetail::class, 'criteria_id');
    }

    // ─── SCOPES ──────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}