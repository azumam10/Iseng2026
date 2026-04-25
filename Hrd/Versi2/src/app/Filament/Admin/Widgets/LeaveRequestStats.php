<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use App\Filament\Admin\Resources\LeaveRequests\LeaveRequestResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class LeaveRequestStats extends BaseWidget
{
    protected function getStats(): array
    {
        $year = Carbon::now()->year;

        $total = LeaveRequest::whereYear('created_at', $year)->count();

        $approved = LeaveRequest::approved()    
            ->whereYear('created_at', $year)
            ->count();

        $rejected = LeaveRequest::rejected()
            ->whereYear('created_at', $year)
            ->count();

        $pending = LeaveRequest::pending()
            ->whereYear('created_at', $year)
            ->count();

        $url = LeaveRequestResource::getUrl('index');

        return [
            Stat::make("Total Pengajuan Cuti {$year}", $total)
                ->description('Semua permintaan cuti tahun ini')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->url($url),

            Stat::make('Disetujui', $approved)
                ->description("Cuti yang sudah disetujui {$year}")
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->url($url),

            Stat::make('Pending', $pending)
                ->description("Menunggu persetujuan HRD {$year}")
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->url($url),

            Stat::make('Ditolak', $rejected)
                ->description("Cuti yang ditolak {$year}")
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->url($url),
        ];
    }

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['hrd', 'admin']);
    }
}