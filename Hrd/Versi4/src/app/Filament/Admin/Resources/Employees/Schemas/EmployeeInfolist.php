<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nik'),
                TextEntry::make('nama'),
                TextEntry::make('no_ktp')
                    ->placeholder('-'),
                TextEntry::make('agama')
                    ->placeholder('-'),
                TextEntry::make('status_karyawan'),
                TextEntry::make('jenis_kelamin')
                    ->badge(),
                TextEntry::make('tanggal_lahir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('pendidikan')
                    ->placeholder('-'),
                TextEntry::make('status_pernikahan')
                    ->placeholder('-'),
                TextEntry::make('tanggal_masuk')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('awal_kontrak')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('akhir_kontrak')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('alamat')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('jabatan')
                    ->placeholder('-'),
                TextEntry::make('department_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('bagian_text')
                    ->placeholder('-'),
                TextEntry::make('kepala_bagian_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
