<div class="card">
    <h2>Form Livewire 🚀</h2>

    <div>
        <label>Nama</label><br>
        <input type="text" wire:model="nama">
        @error('nama')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>

    <br>

    <div>
        <label>Email</label><br>
        <input type="email" wire:model="email">
        @error('email')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>

    <br>

    <button wire:click="submit">
        Simpan
    </button>

    @if($pesan)
        <p style="margin-top:10px;">
            <strong>{{ $pesan }}</strong>
        </p>
    @endif
</div>