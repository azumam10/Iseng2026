<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\PerformanceReviews\Schemas;

use App\Models\Employee;
use App\Models\PerformanceCriteria;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('employee_id')
                    ->label('Karyawan')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function (): array {

                        $user = auth()->user();

                        if ($user->hasRole(['hrd', 'super_admin'])) {
                            return Employee::orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        }

                        $employee = $user->employee;

                        if (! $employee) {
                            return [];
                        }

                        return Employee::where(
                            'supervisor_id',
                            $employee->id
                        )
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    }),

                Select::make('period')
                    ->label('Periode')
                    ->required()
                    ->searchable()
                    ->options(self::generatePeriodOptions()),

                DatePicker::make('review_date')
                    ->label('Tanggal Penilaian')
                    ->required()
                    ->default(now())
                    ->maxDate(now()),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->columnSpanFull(),

                Repeater::make('details')
                    ->relationship('details')
                    ->label('Penilaian Kriteria')
                    ->columnSpanFull()
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->schema([

                        Select::make('criteria_id')
                            ->label('Kriteria')
                            ->disabled()
                            ->dehydrated()
                            ->options(
                                PerformanceCriteria::query()
                                    ->where('is_active', true)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            ),

                        TextInput::make('score')
                            ->label('Skor')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),

                    ])
                    ->default(function (): array {

                        return PerformanceCriteria::query()
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->get()
                            ->map(fn ($criteria) => [
                                'criteria_id' => $criteria->id,
                                'score' => null,
                            ])
                            ->toArray();
                    }),

                Select::make('status')
                    ->label('Status')
                    ->visible(fn () => auth()->user()->hasRole(['hrd', 'super_admin']))
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),

                Textarea::make('rejection_reason')
                    ->label('Alasan Penolakan')
                    ->rows(3)
                    ->visible(fn ($get) => $get('status') === 'rejected'
                    )
                    ->columnSpanFull(),

            ]);
    }

    private static function generatePeriodOptions(): array
    {
        $options = [];

        $year = Carbon::now()->year;

        for ($y = $year; $y >= $year - 1; $y--) {

            for ($q = 4; $q >= 1; $q--) {

                $key = "{$y}-Q{$q}";

                $options[$key] = "{$y} - Triwulan {$q}";
            }
        }

        return $options;
    }
}
