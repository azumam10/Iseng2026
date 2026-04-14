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
        $user = auth()->user();
        $data['requested_by_user_id'] = $user->id;

        if ($user->hasRole('kepala_bagian')) {
            // Kepala Bagian langsung approve tahap 1
            $data['status']            = 'pending_hrd';
            $data['kabag_approved_by'] = $user->id;
        } 
        elseif ($user->hasRole('hrd')) {
            $data['status']           = 'approved';
            $data['hrd_approved_by']  = $user->id;
        } 
        else {
            // Karyawan biasa (meski nanti diblokir)
            $employee = Employee::find($data['employee_id']);
            $data['status'] = $employee && $employee->supervisor_id 
                ? 'pending_head' 
                : 'pending_hrd';
        }

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