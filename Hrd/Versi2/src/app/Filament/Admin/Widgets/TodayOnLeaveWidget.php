<?php

namespace App\Filament\Admin\Widgets;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayOnLeaveWidget extends BaseWidget
{
    protected static ?string $heading   = '🗓️ Karyawan Cuti Hari Ini';
    protected static ?int    $sort      = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $today = Carbon::today()->toDateString();

        return $table
            ->query(
                LeaveRequest::query()
                    ->with(['employee.department', 'leaveType', 'hrdApprovedBy'])
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->orderBy('end_date')
            )
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Nama Karyawan')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('employee.department.name')
                    ->label('Departemen')
                    ->badge()
                    ->color('info'),

                TextColumn::make('leaveType.name')
                    ->label('Jenis Cuti')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('days_until_back')
                    ->label('Kembali')
                    ->getStateUsing(function (LeaveRequest $record): string {
                        $daysLeft = Carbon::today()->diffInDays(
                            Carbon::parse($record->end_date),
                            false
                        );
                        if ($daysLeft === 0) return '🔄 Hari ini';
                        return "dalam {$daysLeft} hari";
                    })
                    ->badge()
                    ->color(fn(string $state): string =>
                        str_contains($state, 'Hari ini') ? 'success' : 'gray'
                    ),

                TextColumn::make('hrdApprovedBy.name')
                    ->label('Disetujui Oleh')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateHeading('Tidak ada karyawan yang cuti hari ini')
            ->emptyStateDescription('Semua karyawan hadir! 🎉')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->striped()
            ->paginated(false);
    }
}