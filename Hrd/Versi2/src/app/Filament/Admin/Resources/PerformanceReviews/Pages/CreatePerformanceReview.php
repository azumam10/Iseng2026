<?php

declare(strict_types=1);
// ── CreatePerformanceReview.php ───────────────────────────────────
// app/Filament/Admin/Resources/PerformanceReviews/Pages/CreatePerformanceReview.php

namespace App\Filament\Admin\Resources\PerformanceReviews\Pages;

use App\Filament\Admin\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePerformanceReview extends CreateRecord
{
    protected static string $resource = PerformanceReviewResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Reviewer otomatis diisi dari user yang sedang login
        $data['reviewer_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
