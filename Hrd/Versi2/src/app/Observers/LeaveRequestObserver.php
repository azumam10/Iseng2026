<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Notifications\Notification;

class LeaveRequestObserver
{
    public function creating(LeaveRequest $leaveRequest): void
    {
        // Hanya blokir jika kuota sudah 0 (bukan hanya kurang)
        $this->checkQuota($leaveRequest, strict: false);
    }

    public function updating(LeaveRequest $leaveRequest): void
    {
        if ($leaveRequest->isDirty('status') && $leaveRequest->status === 'approved') {
            // Saat approve: strict, harus cukup
            $this->checkQuota($leaveRequest, strict: true);
        }
    }

    public function created(LeaveRequest $leaveRequest): void
    {
        $hrdUsers = User::role('hrd')->get();

        foreach ($hrdUsers as $user) {
            Notification::make()
                ->title('Pengajuan cuti baru')
                ->body($leaveRequest->employee->name . ' mengajukan cuti.')
                ->icon('heroicon-o-bell')
                ->sendToDatabase($user);
        }
    }

    private function checkQuota(LeaveRequest $leaveRequest, bool $strict = true): void
    {
        // FIX: Gunakan find() bukan relasi (relasi null saat creating)
        $employee  = Employee::find($leaveRequest->employee_id);
        $leaveType = LeaveType::find($leaveRequest->leave_type_id);

        if (!$employee || !$leaveType || !$leaveType->quota_per_year) {
            return;
        }

        $year = Carbon::parse($leaveRequest->start_date)->year;

        $period        = CarbonPeriod::create($leaveRequest->start_date, $leaveRequest->end_date);
        $requestedDays = $period->filter('isWeekday')->count();

        // Hitung sisa, tapi exclude request yang sedang diedit (saat updating)
        $remaining = $employee->getRemainingLeaveQuota(
            $leaveType->id,
            $year,
            excludeId: $leaveRequest->exists ? $leaveRequest->id : null
        );

        if ($strict && $requestedDays > $remaining) {
            Notification::make()
                ->title('Kuota Cuti Tidak Mencukupi')
                ->body("Sisa kuota: {$remaining} hari kerja | Diajukan: {$requestedDays} hari kerja.")
                ->danger()
                ->persistent()
                ->send();

            throw new \Exception("Kuota cuti tidak mencukupi.");
        }

        // Saat creating (non-strict): hanya blokir jika quota sudah habis total
        if (!$strict && $remaining <= 0) {
            Notification::make()
                ->title('Kuota Cuti Habis')
                ->body("Karyawan tidak memiliki sisa kuota cuti untuk jenis ini.")
                ->danger()
                ->persistent()
                ->send();

            throw new \Exception("Kuota cuti habis.");
        }
    }
}