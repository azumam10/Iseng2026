<div class="max-w-6xl mx-auto py-6 space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Dapartement
            </h1>
            <p class="text-sm text-gray-500">
                Kelola struktur dapartement perusahaan
            </p>
        </div>

        <button
            wire:click="showCreateForm"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            + Tambah Dapartement
        </button>
    </div>

    <!-- FORM -->
    @if ($showForm)
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">
                {{ $isEdit ? 'Edit Dapartement' : 'Tambah Dapartement' }}
            </h2>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nama Dapartement
                    </label>
                    <input
                        type="text"
                        wire:model.defer="nama_dapartement"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Human Resource"
                    >
                    @error('nama_dapartement')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Deskripsi
                    </label>
                    <textarea
                        wire:model.defer="deskripsi"
                        rows="3"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Opsional"
                    ></textarea>
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button
                    wire:click="save"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>

                <button
                    wire:click="resetForm"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
            </div>
        </div>
    @endif

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Dapartement</th>
                    <th class="px-4 py-3 text-left">Deskripsi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($dapartements as $dapartement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $dapartement->nama_dapartement }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $dapartement->deskripsi ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center space-x-2">
                            <button
                                wire:click="edit({{ $dapartement->id }})"
                                class="text-blue-600 hover:underline">
                                Edit
                            </button>

                            <button
                                wire:click="delete({{ $dapartement->id }})"
                                onclick="return confirm('Yakin hapus dapartement ini?')"
                                class="text-red-600 hover:underline">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            Belum ada data dapartement
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>