<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}