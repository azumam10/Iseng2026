<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pegawai', Pegawai::count())
                ->description('Semua pegawai terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Pegawai Aktif', Pegawai::where('status', 'aktif')->count())
                ->description('Sedang bekerja')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pegawai Resign', Pegawai::where('status', 'resign')->count())
                ->description('Total keluar')
                ->descriptionIcon('heroicon-m-arrow-left-on-rectangle')
                ->color('danger'),
        ];
    }
}