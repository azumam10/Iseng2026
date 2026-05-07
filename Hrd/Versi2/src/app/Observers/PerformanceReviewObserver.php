<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\PerformanceReview;
use App\Models\User;
use Filament\Notifications\Notification;

class PerformanceReviewObserver
{
    /**
     * Setelah review dibuat.
     */
    public function created(PerformanceReview $review): void
    {
        $employee = Employee::find($review->employee_id);

        if (! $employee) {
            return;
        }

        $hrdUsers = User::role('hrd')->get();

        foreach ($hrdUsers as $hrdUser) {

            Notification::make()
                ->title('Penilaian Karyawan Baru')
                ->body("{$employee->name} dinilai untuk periode {$review->period}. Menunggu persetujuan HRD.")
                ->icon('heroicon-o-star')
                ->info()
                ->sendToDatabase($hrdUser);
        }
    }

    /**
     * Setelah status berubah.
     */
    public function updated(PerformanceReview $review): void
    {
        if (! $review->wasChanged('status')) {
            return;
        }

        match ($review->status) {
            'approved' => $this->handleApproved($review),
            'rejected' => $this->handleRejected($review),
            default => null,
        };
    }

    /**
     * APPROVED
     */
    private function handleApproved(PerformanceReview $review): void
    {
        $score = $review->calculateWeightedScore();

        /**
         * PENTING:
         * saveQuietly() agar tidak trigger observer updated lagi.
         */
        if ($score !== null) {

            $review->forceFill([
                'score' => $score,
            ])->saveQuietly();

            Employee::where('id', $review->employee_id)
                ->update([
                    'performance_score' => $score,
                    'performance_category' => PerformanceReview::resolveCategory($score),
                ]);
        }

        $employee = $review->employee()
            ->with('user')
            ->first();

        if (! $employee) {
            return;
        }

        $scoreLabel = $score !== null
            ? " Skor: {$score}/100."
            : '';

        /**
         * Notif ke reviewer / kepala bagian
         */
        $reviewer = User::find($review->reviewer_id);

        if ($reviewer) {

            Notification::make()
                ->title('Penilaian Disetujui')
                ->body("Penilaian {$employee->name} periode {$review->period} telah disetujui HRD.{$scoreLabel}")
                ->icon('heroicon-o-check-circle')
                ->success()
                ->sendToDatabase($reviewer);
        }

        /**
         * Notif ke employee
         */
        if ($employee->user) {

            Notification::make()
                ->title('Hasil Penilaian Kinerja')
                ->body("Penilaian kinerja Anda periode {$review->period} telah disetujui.{$scoreLabel}")
                ->icon('heroicon-o-check-circle')
                ->success()
                ->sendToDatabase($employee->user);
        }
    }

    /**
     * REJECTED
     */
    private function handleRejected(PerformanceReview $review): void
    {
        $employee = $review->employee()
            ->with('user')
            ->first();

        if (! $employee) {
            return;
        }

        $reason = $review->rejection_reason
            ? " Alasan: {$review->rejection_reason}"
            : '';

        $reviewer = User::find($review->reviewer_id);

        /**
         * Notif ke reviewer
         */
        if ($reviewer) {

            Notification::make()
                ->title('Penilaian Ditolak')
                ->body("Penilaian {$employee->name} periode {$review->period} ditolak HRD.{$reason}")
                ->icon('heroicon-o-x-circle')
                ->danger()
                ->sendToDatabase($reviewer);
        }

        /**
         * Notif ke employee
         */
        if ($employee->user) {

            Notification::make()
                ->title('Penilaian Ditinjau Ulang')
                ->body("Penilaian kinerja Anda periode {$review->period} sedang ditinjau ulang HRD.")
                ->icon('heroicon-o-information-circle')
                ->warning()
                ->sendToDatabase($employee->user);
        }
    }
}