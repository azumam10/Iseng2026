<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Tables;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;

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

                TextColumn::make('start_date')
                    ->label('Tgl Mulai')
                    ->date(),

                TextColumn::make('end_date')
                    ->label('Tgl Selesai')
                    ->date(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('requestedBy.name')
                    ->label('Diinput Oleh'),

                TextColumn::make('hrdApprovedBy.name')
                    ->label('Disetujui HRD'),
            ])
            ->actions([
                ViewAction::make(),

                // APPROVE HRD
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => 
                        auth()->user()->hasRole('hrd') && 
                        $record->status === 'pending'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'hrd_approved_by' => auth()->id(),
                        ]);
                    }),

                // REJECT HRD
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => 
                        auth()->user()->hasRole('hrd') && 
                        $record->status === 'pending'
                    )
                    ->form([
                        Textarea::make('notes')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejected_by' => auth()->id(),
                            'notes' => $data['notes'],
                        ]);
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}