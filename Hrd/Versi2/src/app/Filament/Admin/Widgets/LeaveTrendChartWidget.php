<?php

namespace App\Filament\Admin\Widgets;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class LeaveTrendChartWidget extends ChartWidget
{
    // Properti di bawah ini harus NON-STATIC
    protected ?string $heading = 'Tren Pengajuan Cuti (12 Bulan Terakhir)';
    protected ?string $maxHeight = '280px';
    protected int|string|array $columnSpan = 'full';

    // Properti $sort biasanya tetap STATIC di Filament
    protected static ?int $sort = 2;

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $years = [];
        for ($y = Carbon::now()->year; $y >= Carbon::now()->year - 3; $y--) {
            $years[(string)$y] = (string)$y;
        }
        return $years;
    }

    protected function getData(): array
    {
        // Set locale ke Indonesia agar nama bulan otomatis dalam bahasa Indonesia
        Carbon::setLocale('id');
        
        $year     = $this->filter ?? Carbon::now()->year;
        $labels   = [];
        $approved = [];
        $pending  = [];
        $rejected = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create($year, $m)->translatedFormat('M');

            // Menghitung jumlah berdasarkan status dan bulan
            $approved[] = LeaveRequest::where('status', 'approved')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $m)
                ->count();

            $pending[] = LeaveRequest::where('status', 'pending')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $m)
                ->count();

            $rejected[] = LeaveRequest::where('status', 'rejected')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $m)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Disetujui',
                    'data'            => $approved,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.15)',
                    'borderColor'     => 'rgb(34, 197, 94)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
                [
                    'label'           => 'Pending',
                    'data'            => $pending,
                    'backgroundColor' => 'rgba(234, 179, 8, 0.15)',
                    'borderColor'     => 'rgb(234, 179, 8)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
                [
                    'label'           => 'Ditolak',
                    'data'            => $rejected,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.15)',
                    'borderColor'     => 'rgb(239, 68, 68)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend'  => ['position' => 'top'],
                'tooltip' => ['mode' => 'index', 'intersect' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                ],
            ],
        ];
    }
}