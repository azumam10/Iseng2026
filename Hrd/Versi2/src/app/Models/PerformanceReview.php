<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceReview extends Model
{
    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_date',
        'period',
        'period_year',
        'period_quarter',
        'score',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'review_date' => 'date',
        'approved_at' => 'datetime',
        'score'       => 'decimal:2',
    ];

    // ─── RELATIONS ────────────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PerformanceReviewDetail::class);
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

    // ─── BUSINESS LOGIC ───────────────────────────────────────────

    /**
     * Hitung weighted average score dari semua detail kriteria.
     *
     * Rumus: Σ(skor_kriteria × bobot_kriteria) / Σ(bobot_kriteria)
     * Dibagi total bobot aktual (bukan 100) agar aman meski
     * total bobot kriteria tidak persis 100.
     *
     * Return null jika belum ada detail sama sekali.
     */
    public function calculateWeightedScore(): ?float
    {
        $details = $this->details()->with('criteria')->get();

        if ($details->isEmpty()) {
            return null;
        }

        $totalWeight  = $details->sum(fn ($d) => $d->criteria->weight);
        $weightedSum  = $details->sum(fn ($d) => $d->score * $d->criteria->weight);

        if ($totalWeight == 0) {
            return null;
        }

        return round($weightedSum / $totalWeight, 2);
    }

    /**
     * Tentukan kategori berdasarkan skor.
     *
     * >= 80 → High
     * >= 60 → Med
     *  < 60 → Low
     */
    public static function resolveCategory(float $score): string
    {
        return match (true) {
            $score >= 80 => 'High',
            $score >= 60 => 'Med',
            default      => 'Low',
        };
    }

    // ─── HOOKS ───────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            // Isi period_year dan period_quarter otomatis dari field period
            // agar tidak perlu input manual. Format period: '2025-Q1'
            [$year, $quarter] = self::parsePeriod($model->period);
            $model->period_year    = $year;
            $model->period_quarter = $quarter;
        });

        static::updating(function (self $model) {
            if ($model->isDirty('period')) {
                [$year, $quarter] = self::parsePeriod($model->period);
                $model->period_year    = $year;
                $model->period_quarter = $quarter;
            }
        });
    }

    /**
     * Parse string period '2025-Q1' menjadi [year, quarter].
     *
     * @return array{int, int}
     */
    private static function parsePeriod(string $period): array
    {
        // Format yang diharapkan: 'YYYY-QN' contoh '2025-Q3'
        preg_match('/^(\d{4})-Q([1-4])$/', $period, $matches);

        if (count($matches) !== 3) {
            throw new \InvalidArgumentException(
                "Format period tidak valid: '{$period}'. Gunakan format YYYY-QN (contoh: 2025-Q1)."
            );
        }

        return [(int) $matches[1], (int) $matches[2]];
    }
}