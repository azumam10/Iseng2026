<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Employee extends Model
{
    protected $fillable = [
        'id_number', 'name', 'position_id', 'department_id', 'section_id',
        'employment_status', 'gender', 'birth_date', 'age', 'generation', 'hire_date',
        'education', 'performance_score', 'performance_category', 'supervisor_id', 'user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date'  => 'date',
    ];

    // ─── RELATIONS ────────────────────────────────────────────────

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    // ─── ACCESSORS ────────────────────────────────────────────────

    /**
     * Hitung usia dari birth_date secara dinamis.
     * Tidak disimpan ke DB, hanya computed.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    /**
     * Hitung generasi berdasarkan usia secara dinamis.
     * Tidak disimpan ke DB, hanya computed.
     *
     * Catatan: kolom 'generation' di $fillable bisa dihapus dari DB
     * jika kamu memutuskan hanya pakai accessor ini.
     */
    public function getGenerationAttribute(): ?string
    {
        $age = $this->birth_date?->age;

        if ($age === null) return null;
        if ($age < 25)     return 'Gen Z';
        if ($age <= 35)    return 'Milenial';
        if ($age <= 45)    return 'Gen X';

        return 'Baby Boomers';
    }

    // ─── LEAVE QUOTA ─────────────────────────────────────────────

    /**
     * Hitung sisa kuota cuti berdasarkan hari kerja (Senin–Jumat).
     * Parameter $excludeId digunakan saat edit agar request yang sedang
     * diedit tidak ikut dihitung sebagai "sudah dipakai".
     */
    public function getRemainingLeaveQuota(int $leaveTypeId, ?int $year = null, ?int $excludeId = null): ?int
    {
        $year      = $year ?? Carbon::now()->year;
        $leaveType = LeaveType::find($leaveTypeId);

        if (! $leaveType || ! $leaveType->quota_per_year) {
            return null; // kuota tidak terbatas
        }

        $usedDays = LeaveRequest::where('employee_id', $this->id)
            ->where('leave_type_id', $leaveTypeId)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->get()
            ->sum(function ($leave) {
                return CarbonPeriod::create($leave->start_date, $leave->end_date)
                    ->filter('isWeekday')
                    ->count();
            });

        return $leaveType->quota_per_year - $usedDays;
    }

    /**
     * Ambil ringkasan saldo semua jenis cuti yang memiliki kuota.
     */
    public function getAllLeaveBalance(?int $year = null): array
    {
        $year = $year ?? Carbon::now()->year;

        return LeaveType::whereNotNull('quota_per_year')
            ->get()
            ->map(function ($leaveType) use ($year) {
                $remaining = $this->getRemainingLeaveQuota($leaveType->id, $year);
                $used      = $leaveType->quota_per_year - $remaining;

                return [
                    'leave_type' => $leaveType->name,
                    'quota'      => $leaveType->quota_per_year,
                    'used'       => $used,
                    'remaining'  => $remaining,
                ];
            })
            ->toArray();
    }
}