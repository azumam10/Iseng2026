<?php

namespace App\Livewire\Dapartement;

use Livewire\Component;
use App\Models\Dapartement;

class Index extends Component
{
    public $showForm = false;
    public $isEdit = false;

    public $dapartementId;
    public $nama_dapartement = '';
    public $deskripsi = '';

    protected function rules()
    {
        return [
            'nama_dapartement' => 'required|min:3|unique:dapartements,nama_dapartement,' . $this->dapartementId,
            'deskripsi' => 'nullable',
        ];
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showForm = true;
    }

    public function edit($id)
    {
        $dapartement = Dapartement::findOrFail($id);

        $this->dapartementId = $dapartement->id;
        $this->nama_dapartement = $dapartement->nama_dapartement;
        $this->deskripsi = $dapartement->deskripsi;

        $this->isEdit = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        Dapartement::updateOrCreate(
            ['id' => $this->dapartementId],
            [
                'nama_dapartement' => $this->nama_dapartement,
                'deskripsi' => $this->deskripsi,
            ]
        );

        $this->resetForm();
    }

    public function delete($id)
    {
        Dapartement::findOrFail($id)->delete();
    }

    public function resetForm()
    {
        $this->dapartementId = null;
        $this->nama_dapartement = '';
        $this->deskripsi = '';
        $this->showForm = false;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.dapartement.index', [
            'dapartements' => Dapartement::latest()->get()
        ]);
    }
}