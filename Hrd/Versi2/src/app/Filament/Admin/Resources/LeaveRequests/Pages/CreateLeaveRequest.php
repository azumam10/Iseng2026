<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Pages;

use App\Filament\Admin\Resources\LeaveRequests\LeaveRequestResource;
use App\Models\Employee;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['requested_by_user_id'] = auth()->id();
    $data['status'] = 'pending';

    return $data;
}

    // Blokir karyawan membuat request
    protected function authorizeAccess(): void
    {
        if (auth()->user()->hasRole('employee')) {
            abort(403, 'Karyawan tidak diperbolehkan membuat permintaan cuti melalui aplikasi.');
        }
    }
}