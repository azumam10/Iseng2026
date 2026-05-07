<?php

namespace App\Filament\Admin\Resources\PerformanceReviews;

use App\Filament\Admin\Resources\PerformanceReviews\Pages\CreatePerformanceReview;
use App\Filament\Admin\Resources\PerformanceReviews\Pages\EditPerformanceReview;
use App\Filament\Admin\Resources\PerformanceReviews\Pages\ListPerformanceReviews;
use App\Filament\Admin\Resources\PerformanceReviews\Pages\ViewPerformanceReview;
use App\Filament\Admin\Resources\PerformanceReviews\Schemas\PerformanceReviewForm;
use App\Filament\Admin\Resources\PerformanceReviews\Tables\PerformanceReviewsTable;
use App\Models\PerformanceReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PerformanceReviewResource extends Resource
{
    protected static ?string $model = PerformanceReview::class;

    protected static string|UnitEnum|null $navigationGroup = 'General';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Penilaian Karyawan';

    protected static ?string $pluralModelLabel = 'Penilaian Karyawan';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PerformanceReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerformanceReviewsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery()
        ->with([
            'employee',
            'reviewer',
            'details.criteria',
        ]);

    $user = auth()->user();

    if ($user->hasAnyRole(['super_admin', 'hrd'])) {
        return $query;
    }

    if ($user->hasRole('kepala_bagian')) {
        return $query->where('reviewer_id', $user->id);
    }

    if ($user->hasRole('employee')) {
        return $query->where('employee_id', $user->employee?->id);
    }

    return $query->whereRaw('1 = 0');
}

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole([
            'super_admin',
            'kepala_bagian',
            'hrd',
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPerformanceReviews::route('/'),
            'create' => CreatePerformanceReview::route('/create'),
            'edit' => EditPerformanceReview::route('/{record}/edit'),
            'view' => ViewPerformanceReview::route('/{record}'),
        ];
    }
}