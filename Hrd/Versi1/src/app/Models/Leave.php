<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Leave extends Model
{
    protected $fillable = [
        'pegawai_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'document_path',
        'status',
        'hr_note',
        'approved_by',
        'approved_at',
        'cancelled_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function usedLeaveDays($pegawaiId, $year)
    {
        return self::where('pegawai_id', $pegawaiId)
        ->whereYear('start_date', $year)
        ->where('status', 'approved')
        ->whereNull('cancelled_at')
        ->sum('total_days');
        }
    
}