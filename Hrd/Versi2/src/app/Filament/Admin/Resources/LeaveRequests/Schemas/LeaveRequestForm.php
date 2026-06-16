<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\LeaveRequests\Schemas;

use App\Models\Employee;
use App\Models\LeaveType;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
// use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

final class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship(
                        name: 'employee',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            $user = auth()->user();
                            if ($user->hasRole('kepala_bagian') && $user->employee) {
                                return $query->where('supervisor_id', $user->employee->id);
                            }

                            return $query;
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(), // ← wajib untuk reactive

                Select::make('leave_type_id')
                    ->label('Jenis Cuti')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(), // ← wajib untuk reactive

                // --- SISA KUOTA ---
                Placeholder::make('sisa_kuota')
                    ->label('Sisa Kuota Cuti (Tahun Ini)')
                    ->content(function (Get $get): string {
                        $employeeId = $get('employee_id');
                        $leaveTypeId = $get('leave_type_id');

                        if (! $employeeId || ! $leaveTypeId) {
                            return '— Pilih karyawan dan jenis cuti —';
                        }

                        $employee = Employee::find($employeeId);
                        $leaveType = LeaveType::find($leaveTypeId);

                        if (! $leaveType?->quota_per_year) {
                            return 'Tidak ada batas kuota';
                        }

                        $remaining = $employee?->getRemainingLeaveQuota($leaveTypeId);
                        $quota = $leaveType->quota_per_year;
                        $used = $quota - $remaining;

                        $color = match (true) {
                            $remaining <= 0 => '🔴',
                            $remaining <= 3 => '🟡',
                            default => '🟢',
                        };

                        return "{$color} Sisa: {$remaining} hari kerja (Dipakai: {$used} / Kuota: {$quota} hari)";
                    }),

                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live(), // ← untuk reactive hari diajukan

                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->afterOrEqual('start_date')
                    ->live(), // ← untuk reactive hari diajukan

                // --- HARI KERJA DIAJUKAN ---
                Placeholder::make('hari_diajukan')
                    ->label('Hari Kerja Diajukan')
                    ->content(function (Get $get): string {
                        $start = $get('start_date');
                        $end = $get('end_date');

                        if (! $start || ! $end) {
                            return '—';
                        }

                        $period = CarbonPeriod::create($start, $end);
                        $days = $period->filter('isWeekday')->count();

                        return "{$days} hari kerja (Sabtu & Minggu tidak dihitung)";
                    }),

                Textarea::make('reason')
                    ->label('Alasan')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('document')
                    ->label('Dokumen Pendukung')
                    ->directory('leave-documents')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
                    ->maxSize(2048)
                    ->downloadable()
                    ->openable()
                    ->helperText('Upload surat sakit, surat keterangan (PDF, JPG, PNG, maks 2MB)')
                    ->columnSpanFull(),
            ]);
    }
}
