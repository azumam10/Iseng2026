<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class LeaveRequestObserver
{
    // ─── CREATING ─────────────────────────────────────────────────

    /**
     * Saat creating: hanya blokir jika kuota sudah benar-benar habis (0).
     * Belum strict karena HRD yang nanti approve/reject.
     */
    public function creating(LeaveRequest $leaveRequest): void
    {
        $this->checkQuota($leaveRequest, strict: false);
    }

    /**
     * Setelah berhasil dibuat: kirim notifikasi ke semua user HRD.
     */
    public function created(LeaveRequest $leaveRequest): void
    {
        $employee = Employee::find($leaveRequest->employee_id);

        if (! $employee) {
            return;
        }

        $hrdUsers = User::role('hrd')->get();

        foreach ($hrdUsers as $hrdUser) {
            Notification::make()
                ->title('Pengajuan Cuti Baru')
                ->body("{$employee->name} mengajukan cuti mulai {$leaveRequest->start_date->format('d/m/Y')} s.d. {$leaveRequest->end_date->format('d/m/Y')}.")
                ->icon('heroicon-o-calendar-days')
                ->info()
                ->sendToDatabase($hrdUser);
        }
    }

    // ─── UPDATING ─────────────────────────────────────────────────

    /**
     * Saat status berubah menjadi approved: cek kuota secara strict,
     * lalu kirim notifikasi ke kepala bagian dan employee terkait.
     */
    public function updating(LeaveRequest $leaveRequest): void
    {
        if (! $leaveRequest->isDirty('status')) {
            return;
        }

        if ($leaveRequest->status === 'approved') {
            $this->checkQuota($leaveRequest, strict: true);
        }
    }

    /**
     * Setelah status berhasil diupdate: kirim notifikasi yang sesuai.
     */
    public function updated(LeaveRequest $leaveRequest): void
    {
        if (! $leaveRequest->wasChanged('status')) {
            return;
        }

        match ($leaveRequest->status) {
            'approved' => $this->notifyApproved($leaveRequest),
            'rejected' => $this->notifyRejected($leaveRequest),
            default    => null,
        };
    }

    // ─── PRIVATE HELPERS ──────────────────────────────────────────

    /**
     * Cek kuota cuti.
     *
     * Mode strict  = harus cukup (dipakai saat approve).
     * Mode non-strict = hanya blokir jika sudah 0 total (dipakai saat creating).
     *
     * @throws ValidationException
     */
    private function checkQuota(LeaveRequest $leaveRequest, bool $strict = true): void
    {
        $employee  = Employee::find($leaveRequest->employee_id);
        $leaveType = LeaveType::find($leaveRequest->leave_type_id);

        if (! $employee || ! $leaveType || ! $leaveType->quota_per_year) {
            return;
        }

        $requestedDays = CarbonPeriod::create($leaveRequest->start_date, $leaveRequest->end_date)
            ->filter('isWeekday')
            ->count();

        $year      = Carbon::parse($leaveRequest->start_date)->year;
        $remaining = $employee->getRemainingLeaveQuota(
            $leaveType->id,
            $year,
            excludeId: $leaveRequest->exists ? $leaveRequest->id : null,
        );

        if ($strict && $requestedDays > $remaining) {
            Notification::make()
                ->title('Kuota Cuti Tidak Mencukupi')
                ->body("Sisa kuota: {$remaining} hari kerja | Diajukan: {$requestedDays} hari kerja.")
                ->danger()
                ->persistent()
                ->send();

            throw ValidationException::withMessages([
                'leave_type_id' => "Kuota cuti tidak mencukupi. Sisa: {$remaining} hari kerja.",
            ]);
        }

        if (! $strict && $remaining <= 0) {
            Notification::make()
                ->title('Kuota Cuti Habis')
                ->body("Karyawan tidak memiliki sisa kuota cuti untuk jenis ini.")
                ->danger()
                ->persistent()
                ->send();

            throw ValidationException::withMessages([
                'leave_type_id' => 'Kuota cuti untuk jenis ini sudah habis.',
            ]);
        }
    }

    /**
     * Kirim notifikasi approved ke:
     * - User kepala bagian yang menginput (requested_by_user_id)
     * - User employee yang bersangkutan (via employee->user)
     */
    private function notifyApproved(LeaveRequest $leaveRequest): void
    {
        $employee = $leaveRequest->employee()->with('user')->first();

        $title = 'Cuti Disetujui';
        $body  = "Pengajuan cuti {$employee->name} ({$leaveRequest->start_date->format('d/m/Y')} – {$leaveRequest->end_date->format('d/m/Y')}) telah disetujui.";

        // Notifikasi ke kepala bagian yang menginput
        $kabag = User::find($leaveRequest->requested_by_user_id);

        if ($kabag) {
            Notification::make()
                ->title($title)
                ->body($body)
                ->icon('heroicon-o-check-circle')
                ->success()
                ->sendToDatabase($kabag);
        }

        // Notifikasi ke employee (jika punya akun)
        if ($employee?->user) {
            Notification::make()
                ->title('Cuti Anda Disetujui')
                ->body("Pengajuan cuti Anda ({$leaveRequest->start_date->format('d/m/Y')} – {$leaveRequest->end_date->format('d/m/Y')}) telah disetujui oleh HRD.")
                ->icon('heroicon-o-check-circle')
                ->success()
                ->sendToDatabase($employee->user);
        }
    }

    /**
     * Kirim notifikasi rejected ke:
     * - User kepala bagian yang menginput
     * - User employee yang bersangkutan
     */
    private function notifyRejected(LeaveRequest $leaveRequest): void
    {
        $employee = $leaveRequest->employee()->with('user')->first();

        $title = 'Cuti Ditolak';
        $body  = "Pengajuan cuti {$employee->name} ({$leaveRequest->start_date->format('d/m/Y')} – {$leaveRequest->end_date->format('d/m/Y')}) ditolak.";

        // Notifikasi ke kepala bagian yang menginput
        $kabag = User::find($leaveRequest->requested_by_user_id);

        if ($kabag) {
            Notification::make()
                ->title($title)
                ->body($body)
                ->icon('heroicon-o-x-circle')
                ->danger()
                ->sendToDatabase($kabag);
        }

        // Notifikasi ke employee (jika punya akun)
        if ($employee?->user) {
            Notification::make()
                ->title('Cuti Anda Ditolak')
                ->body("Pengajuan cuti Anda ({$leaveRequest->start_date->format('d/m/Y')} – {$leaveRequest->end_date->format('d/m/Y')}) ditolak oleh HRD.")
                ->icon('heroicon-o-x-circle')
                ->danger()
                ->sendToDatabase($employee->user);
        }
    }
}