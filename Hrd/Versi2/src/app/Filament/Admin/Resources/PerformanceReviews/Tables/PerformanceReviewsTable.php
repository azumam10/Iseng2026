<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\PerformanceReviews\Tables;

use App\Models\PerformanceReview;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class PerformanceReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('employee.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('period')
                    ->label('Periode')
                    ->sortable(),

                TextColumn::make('score')
                    ->label('Nilai')
                    ->sortable(),

                TextColumn::make('reviewer.name')
                    ->label('Reviewer'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('review_date')
                    ->date('d M Y'),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

            ])

            ->recordActions([

                ViewAction::make(),

                EditAction::make()
                    ->visible(fn (PerformanceReview $record) => $record->status === 'pending'
                        || auth()->user()->hasRole('hrd')
                    ),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->databaseTransaction(false)
                    ->visible(fn (PerformanceReview $record) => auth()->user()->hasAnyRole(['hrd', 'super_admin'])
                    && $record->status === 'pending'
                    )
                    ->requiresConfirmation()
                    ->action(function (PerformanceReview $record) {

                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Penilaian disetujui')
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->databaseTransaction(false)
                    ->visible(fn (PerformanceReview $record) => auth()->user()->hasAnyRole(['hrd', 'super_admin'])
                    && $record->status === 'pending'
                    )
                    ->form([

                        Textarea::make('rejection_reason')
                            ->required()
                            ->label('Alasan Penolakan'),

                    ])
                    ->action(function (
                        PerformanceReview $record,
                        array $data
                    ) {

                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Penilaian ditolak')
                            ->send();
                    }),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
