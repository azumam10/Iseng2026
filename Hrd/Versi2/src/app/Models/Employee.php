<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Employee extends Model
{
    protected $fillable = [
        'id_number', 'name', 'position_id', 'department_id', 'section_id',
        'employment_status', 'gender', 'birth_date', 'age', 'generation', 'hire_date',
        'education', 'performance_score', 'performance_category', 'supervisor_id', 'user_id'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
    ];

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

    // Accessor untuk menghitung usia jika tidak pakai virtual column
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    // Mutator untuk mengisi generasi otomatis
    public function setGenerationAttribute()
    {
        $age = $this->age;
        if ($age < 25) return 'Gen Z';
        if ($age <= 35) return 'Milenial';
        if ($age <= 45) return 'Gen X';
        return 'Baby Boomers';
    }

    public function getGenerationAttribute()
{
    $age = $this->age;
    if ($age < 25) return 'Gen Z';
    if ($age <= 35) return 'Milenial';
    if ($age <= 45) return 'Gen X';
    return 'Baby Boomers';
}

// HITUNG SISA CUTI
/* fungsi jika hari kerja sabtu dan minggu masuk
public function getRemainingLeaveQuota($leaveTypeId, $year = null)
{
    $year = $year ?? Carbon::now()->year;
    $leaveType = \App\Models\LeaveType::find($leaveTypeId);
    
    if (!$leaveType || !$leaveType->quota_per_year) {
        return null; // tidak terbatas
    }
    
    $usedDays = LeaveRequest::where('employee_id', $this->id)
        ->where('leave_type_id', $leaveTypeId)
        ->where('status', 'approved')
        ->whereYear('start_date', $year)
        ->get()
        ->sum(fn($leave) => Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1);
    
    return $leaveType->quota_per_year - $usedDays;
}*/
// hitung cuti jika sabtu dan minggu libur
// Ganti fungsi getRemainingLeaveQuota yang lama dengan ini:
public function getRemainingLeaveQuota(int $leaveTypeId, ?int $year = null, ?int $excludeId = null): ?int
{
    $year = $year ?? Carbon::now()->year;
    $leaveType = \App\Models\LeaveType::find($leaveTypeId);

    if (!$leaveType || !$leaveType->quota_per_year) {
        return null;
    }

    $usedDays = LeaveRequest::where('employee_id', $this->id)
        ->where('leave_type_id', $leaveTypeId)
        ->where('status', 'approved')
        ->whereYear('start_date', $year)
        ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
        ->get()
        ->sum(function ($leave) {
            $period = CarbonPeriod::create($leave->start_date, $leave->end_date);
            return $period->filter('isWeekday')->count();
        });

    return $leaveType->quota_per_year - $usedDays;
}

// Tambah method untuk summary semua jenis cuti
public function getAllLeaveBalance(?int $year = null): array
{
    $year = $year ?? Carbon::now()->year;

    return \App\Models\LeaveType::whereNotNull('quota_per_year')
        ->get()
        ->map(function ($leaveType) use ($year) {
            $remaining = $this->getRemainingLeaveQuota($leaveType->id, $year);
            $used      = $leaveType->quota_per_year - $remaining;

            return [
                'leave_type'    => $leaveType->name,
                'quota'         => $leaveType->quota_per_year,
                'used'          => $used,
                'remaining'     => $remaining,
            ];
        })
        ->toArray();
}


}