<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Leave;
use Filament\Notifications\Notification;
use Carbon\Carbon;


class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function beforeCreate(): void
{
    $start = Carbon::parse($this->data['start_date']);
    $end   = Carbon::parse($this->data['end_date']);

    // hitung jumlah hari cuti
    $totalDays = $start->diffInDays($end) + 1;

    // inject ke data form
    $this->data['total_days'] = $totalDays;

    $pegawaiId = $this->data['pegawai_id'];
    $year = now()->year;

    $used = Leave::usedLeaveDays($pegawaiId, $year);

    if (($used + $totalDays) > 10) {

        Notification::make()
            ->title('Sisa cuti tidak cukup')
            ->danger()
            ->send();

        $this->halt();
    }
}
}
