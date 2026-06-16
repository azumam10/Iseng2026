<?php

declare(strict_types=1);
// ── ViewPerformanceReview.php ─────────────────────────────────────
// app/Filament/Admin/Resources/PerformanceReviews/Pages/ViewPerformanceReview.php

namespace App\Filament\Admin\Resources\PerformanceReviews\Pages;

use App\Filament\Admin\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewPerformanceReview extends ViewRecord
{
    protected static string $resource = PerformanceReviewResource::class;
}
