<?php

namespace App\Filament\Exports;

use App\Models\Pegawai;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PegawaiExporter extends Exporter
{
    protected static ?string $model = Pegawai::class;

   public static function getColumns(): array
{
    return [
        ExportColumn::make('nip')->label('NIP'),
        ExportColumn::make('nama_lengkap'),
        ExportColumn::make('email'),
        ExportColumn::make('departemen.nama')->label('Departemen'),
        ExportColumn::make('jabatan.nama')->label('Jabatan'),
        ExportColumn::make('status'),
    ];
}

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pegawai export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
