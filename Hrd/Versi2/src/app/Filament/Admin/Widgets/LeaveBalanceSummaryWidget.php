<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Employee;
use App\Models\LeaveType;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

final class LeaveBalanceSummaryWidget extends BaseWidget
{
    protected static ?string $heading = 'Rekap Sisa Kuota Cuti Karyawan';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $year = Carbon::now()->year;
        $leaveTypes = LeaveType::whereNotNull('quota_per_year')->get();

        $columns = [
            TextColumn::make('id_number')
                ->label('NIK')
                ->searchable()
                ->sortable()
                ->weight('medium')
                ->copyable(),

            TextColumn::make('name')
                ->label('Nama Karyawan')
                ->searchable()
                ->sortable()
                ->weight('bold'),

            TextColumn::make('department.name')
                ->label('Departemen')
                ->badge()
                ->color('gray')
                ->sortable(),
        ];

        foreach ($leaveTypes as $lt) {
            $ltId = $lt->id;
            $quota = $lt->quota_per_year;

            $columns[] = TextColumn::make("balance_{$ltId}")
                ->label("{$lt->name} ({$quota}h)")
                ->getStateUsing(function (Employee $record) use ($ltId, $year, $quota): string {
                    $remaining = $record->getRemainingLeaveQuota($ltId, $year) ?? $quota;

                    return "{$remaining} sisa";
                })
                ->badge()
                ->color(function (Employee $record) use ($ltId, $year, $quota): string {
                    $remaining = $record->getRemainingLeaveQuota($ltId, $year) ?? $quota;
                    if ($remaining <= 0) {
                        return 'danger';
                    }
                    if ($remaining <= 3) {
                        return 'warning';
                    }

                    return 'success';
                })
                ->tooltip(function (Employee $record) use ($ltId, $year, $quota): string {
                    $remaining = $record->getRemainingLeaveQuota($ltId, $year) ?? $quota;
                    $used = $quota - $remaining;

                    return "Dipakai: {$used} | Sisa: {$remaining} | Kuota: {$quota}";
                });
        }

        return $table
            ->query(
                Employee::query()
                    ->with(['department', 'leaveRequests.leaveType'])
                    ->orderBy('name')
            )
            ->columns($columns)
            ->filters([
                SelectFilter::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'name'),
            ])
            ->paginated([10, 25, 50])
            ->striped();
    }
}
