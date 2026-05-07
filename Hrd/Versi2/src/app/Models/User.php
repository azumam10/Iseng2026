<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'avatar_url',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ─── FILAMENT ─────────────────────────────────────────────────

    public function getFilamentAvatarUrl(): string
    {
        if ($this->avatar_url) {
            return asset('storage/' . $this->avatar_url);
        }

        $hash = md5(mb_strtolower(mb_trim($this->email)));

        return "https://www.gravatar.com/avatar/{$hash}?d=mp&r=g&s=250";
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    // ─── RELATIONS ────────────────────────────────────────────────

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function performanceReviewsAsReviewer(): HasMany
    {
        return $this->hasMany(PerformanceReview::class, 'reviewer_id');
    }

    public function performanceReviewsAsApprover(): HasMany
    {
        return $this->hasMany(PerformanceReview::class, 'approved_by');
    }

    /**
     * Cuti yang diinput oleh user ini (sebagai kepala bagian).
     */
    public function leaveRequestsAsRequester(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'requested_by_user_id');
    }

    /**
     * Cuti yang di-approve oleh user ini (sebagai HRD).
     */
    public function leaveRequestsAsHrd(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'hrd_approved_by');
    }

    /**
     * Cuti yang di-reject oleh user ini.
     */
    public function leaveRequestsAsRejector(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'rejected_by');
    }

    // ─── CASTS ────────────────────────────────────────────────────

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}