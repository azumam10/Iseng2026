<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPegawai extends ViewRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('buka_gdrive')
                ->label('Google Drive')
                ->icon('heroicon-m-folder-open')
                ->color('gray')
                ->url(fn () => $this->record->gdrive_folder_link)
                ->openUrlInNewTab()
                ->visible(fn () => filled($this->record->gdrive_folder_link)),

            EditAction::make(),
        ];
    }
}