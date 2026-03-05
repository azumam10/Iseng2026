<?php

namespace App\Filament\Admin\Resources\PerformanceReviews\Pages;

use App\Filament\Admin\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPerformanceReview extends ViewRecord
{
    protected static string $resource = PerformanceReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
