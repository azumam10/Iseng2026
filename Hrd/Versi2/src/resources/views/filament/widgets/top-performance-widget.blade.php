<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Peringkat Kinerja Karyawan</x-slot>

        <x-slot name="description">Berdasarkan skor penilaian terakhir yang telah disetujui</x-slot>

        {{-- Grid dua kolom: tertinggi | terendah --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- ── TERTINGGI ─────────────────────────────────── --}}
            <div>
                <div class="mb-3 flex items-center gap-2">
                    <span
                        class="bg-success-100 dark:bg-success-900 inline-flex items-center justify-center rounded-full p-1.5"
                    >
                        <x-heroicon-o-arrow-trending-up class="text-success-600 dark:text-success-400 h-4 w-4" />
                    </span>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">10 Kinerja Terbaik</h3>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                    #
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Nama
                                </th>
                                <th
                                    class="hidden px-3 py-2 text-left text-xs font-medium text-gray-500 sm:table-cell dark:text-gray-400"
                                >
                                    Departemen
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Skor
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Kategori
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($this->getTopHighest() as $index => $employee)
                                <tr
                                    class="bg-white transition hover:bg-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800"
                                >
                                    <td class="px-3 py-2.5">
                                        @if ($index === 0)
                                            <span class="text-base">🥇</span>
                                        @elseif ($index === 1)
                                            <span class="text-base">🥈</span>
                                        @elseif ($index === 2)
                                            <span class="text-base">🥉</span>
                                        @else
                                            <span class="text-xs font-medium text-gray-400">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $employee->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $employee->position?->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td
                                        class="hidden px-3 py-2.5 text-xs text-gray-500 sm:table-cell dark:text-gray-400"
                                    >
                                        {{ $employee->department?->name ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        {{-- Progress bar skor --}}
                                        <div class="flex items-center justify-center gap-2">
                                            <div
                                                class="h-1.5 w-16 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700"
                                            >
                                                <div
                                                    class="bg-success-500 h-full rounded-full"
                                                    style="width: {{ $employee->performance_score }}%"
                                                ></div>
                                            </div>
                                            <span
                                                class="text-success-600 dark:text-success-400 min-w-[2.5rem] text-xs font-semibold"
                                            >
                                                {{ number_format($employee->performance_score, 1) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        @php
                                            $cat = $employee->performance_category;
                                            $color = match ($cat) {
                                                'High' => 'bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300',
                                                'Med' => 'bg-warning-100 text-warning-700 dark:bg-warning-900 dark:text-warning-300',
                                                'Low' => 'bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp

                                        <span
                                            class="{{ $color }} inline-block rounded-full px-2 py-0.5 text-xs font-medium"
                                        >
                                            {{ $cat ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-400">
                                        Belum ada data penilaian
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── TERENDAH ──────────────────────────────────── --}}
            <div>
                <div class="mb-3 flex items-center gap-2">
                    <span
                        class="bg-danger-100 dark:bg-danger-900 inline-flex items-center justify-center rounded-full p-1.5"
                    >
                        <x-heroicon-o-arrow-trending-down class="text-danger-600 dark:text-danger-400 h-4 w-4" />
                    </span>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">10 Kinerja Perlu Perhatian</h3>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                    #
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Nama
                                </th>
                                <th
                                    class="hidden px-3 py-2 text-left text-xs font-medium text-gray-500 sm:table-cell dark:text-gray-400"
                                >
                                    Departemen
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Skor
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Kategori
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($this->getTopLowest() as $index => $employee)
                                <tr
                                    class="bg-white transition hover:bg-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800"
                                >
                                    <td class="px-3 py-2.5">
                                        <span class="text-xs font-medium text-gray-400">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $employee->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $employee->position?->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td
                                        class="hidden px-3 py-2.5 text-xs text-gray-500 sm:table-cell dark:text-gray-400"
                                    >
                                        {{ $employee->department?->name ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div
                                                class="h-1.5 w-16 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700"
                                            >
                                                <div
                                                    class="bg-danger-500 h-full rounded-full"
                                                    style="width: {{ $employee->performance_score }}%"
                                                ></div>
                                            </div>
                                            <span
                                                class="text-danger-600 dark:text-danger-400 min-w-[2.5rem] text-xs font-semibold"
                                            >
                                                {{ number_format($employee->performance_score, 1) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        @php
                                            $cat = $employee->performance_category;
                                            $color = match ($cat) {
                                                'High' => 'bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300',
                                                'Med' => 'bg-warning-100 text-warning-700 dark:bg-warning-900 dark:text-warning-300',
                                                'Low' => 'bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp

                                        <span
                                            class="{{ $color }} inline-block rounded-full px-2 py-0.5 text-xs font-medium"
                                        >
                                            {{ $cat ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-400">
                                        Belum ada data penilaian
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
