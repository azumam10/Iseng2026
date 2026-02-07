<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;

class CustomLogin extends Login
{
    // 1. Mengubah Judul Halaman
    public function getHeading(): string | Htmlable
    {
        return 'Portal PT Sankei Medical Industries';
    }

    // 2. Menambah Sub-judul (pesan selamat datang)
    public function getSubheading(): string | Htmlable | null
    {
        return 'Silakan login';
    }

    // 3. (Opsional) Mengubah field Email jadi "NIP" atau lainnya jika perlu
    // Jika tidak ingin diubah, hapus function form() ini.
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(), 
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}