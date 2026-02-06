<?php

namespace App\Livewire;

use Livewire\Component;

class Hello extends Component
{
    public string $nama = '';
    public string $email = '';
    public string $pesan = '';

    protected $rules = [
        'nama'  => 'required|min:3',
        'email' => 'required|email',
    ];

    public function submit()
    {
        $this->validate();

        $this->pesan = "Data valid: {$this->nama} ({$this->email})";
    }

    public function render()
    {
        return view('livewire.hello');
    }
}