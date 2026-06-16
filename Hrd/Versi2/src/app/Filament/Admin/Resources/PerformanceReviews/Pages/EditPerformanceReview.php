<?php

declare(strict_types=1);
// ── EditPerformanceReview.php ─────────────────────────────────────
// app/Filament/Admin/Resources/PerformanceReviews/Pages/EditPerformanceReview.php

namespace App\Filament\Admin\Resources\PerformanceReviews\Pages;

use App\Filament\Admin\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

final class EditPerformanceReview extends EditRecord
{
    protected static string $resource = PerformanceReviewResource::class;

    /**
     * Kepala bagian tidak boleh edit review yang sudah approved/rejected.
     */
    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();

        $user = auth()->user();
        $record = $this->getRecord();

        if (
            ! $user->hasRole('hrd') &&
            $record->status !== 'pending'
        ) {
            throw ValidationException::withMessages([
                'status' => 'Penilaian yang sudah diproses tidak dapat diubah.',
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
