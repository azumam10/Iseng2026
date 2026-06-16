<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Models\Employee;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

final class TopPerformanceWidget extends Widget
{
    protected string $view = 'filament.widgets.top-performance-widget';

    // Widget ini lebar penuh
    protected int|string|array $columnSpan = 'full';

    // Urutan tampil di dashboard
    protected static ?int $sort = 5;

    // Hanya HRD dan super_admin yang bisa lihat widget ini
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'hrd']);
    }

    /**
     * 10 karyawan dengan skor tertinggi (hanya yang sudah punya skor).
     */
    public function getTopHighest(): Collection
    {
        return Employee::query()
            ->whereNotNull('performance_score')
            ->with(['department', 'position'])
            ->orderByDesc('performance_score')
            ->limit(10)
            ->get();
    }

    /**
     * 10 karyawan dengan skor terendah.
     */
    public function getTopLowest(): Collection
    {
        return Employee::query()
            ->whereNotNull('performance_score')
            ->with(['department', 'position'])
            ->orderBy('performance_score')
            ->limit(10)
            ->get();
    }
}
