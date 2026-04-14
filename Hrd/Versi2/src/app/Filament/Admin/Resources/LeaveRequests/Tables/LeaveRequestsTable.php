<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Karyawan')
                    ->searchable(),

                TextColumn::make('leaveType.name')
                    ->label('Jenis Cuti'),

                TextColumn::make('start_date')->date(),
                TextColumn::make('end_date')->date(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_head' => 'warning',
                        'pending_hrd'  => 'info',
                        'approved'     => 'success',
                        'rejected'     => 'danger',
                        default        => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat'),
            ])

            ->recordActions([
                ViewAction::make(),

                // === APPROVE KEPALA BAGIAN ===
                Action::make('approve_head')
                    ->label('Approve (Kabag)')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) =>
                        auth()->user()->hasRole('kepala_bagian') &&
                        $record->status === 'pending_head'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status'            => 'pending_hrd',
                            'kabag_approved_by' => auth()->id(),
                        ]);
                    }),

                // === APPROVE HRD ===
                Action::make('approve_hrd')
                    ->label('Approve (HRD)')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) =>
                        auth()->user()->hasRole('hrd') &&
                        $record->status === 'pending_hrd'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status'          => 'approved',
                            'hrd_approved_by' => auth()->id(),
                        ]);
                    }),

                // === REJECT (HRD) ===
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn () => auth()->user()->hasRole('hrd'))
                    ->requiresConfirmation()
                    ->form([
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Alasan Ditolak')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'       => 'rejected',
                            'rejected_by'  => auth()->id(),
                            'notes'        => $data['notes'],
                        ]);
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}