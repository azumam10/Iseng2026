<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Departments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('name')
                    ->label('Nama Departemen')
                    ->copyable()
                    ->weight('bold'),

                TextEntry::make('code')
                    ->label('Kode Departemen')
                    ->placeholder('-')
                    ->badge()
                    ->color('primary'),

                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d F Y H:i:s')
                    ->icon('heroicon-o-calendar')
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d F Y H:i:s')
                    ->icon('heroicon-o-arrow-path')
                    ->placeholder('-'),

                TextEntry::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d F Y H:i:s')
                    ->icon('heroicon-o-trash')
                    ->placeholder('Masih aktif'),
            ]);
    }
}
