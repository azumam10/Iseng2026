<?php

declare(strict_types=1);
// ── ListPerformanceReviews.php ────────────────────────────────────
// app/Filament/Admin/Resources/PerformanceReviews/Pages/ListPerformanceReviews.php

namespace App\Filament\Admin\Resources\PerformanceReviews\Pages;

use App\Filament\Admin\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListPerformanceReviews extends ListRecords
{
    protected static string $resource = PerformanceReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => ! auth()->user()->hasRole('employee')),
        ];
    }
}
