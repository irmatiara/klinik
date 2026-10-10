<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Ruang Dokter: Examination & Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Flash Message -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl p-6 border border-hfc-primary/20 shadow-lg shadow-hfc-primary/5 flex items-center justify-between relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-hfc-primary/5 rounded-full blur-2xl pointer-events-none"></div>
                <div>
                    <h3 class="text-2xl font-bold text-hfc-dark mt-1">Pemeriksaan Dokter & Input Rekam Medis</h3>
                    <p class="text-gray-600 text-sm mt-1">Dokter memeriksa diagnosa pasien dan menginput resep obat untuk kasir & farmasi.</p>
                </div>
            </div>

            <!-- Card Filter & Search -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6 flex justify-end">
                    <form method="GET" action="{{ route('rekam-medis.index') }}" class="flex flex-col sm:flex-row items-center justify-end gap-3 w-full sm:w-auto">
                        <select name="status" class="w-full sm:w-auto text-sm border-gray-200 rounded-xl focus:border-hfc-primary focus:ring-hfc-primary py-2.5 px-3 font-medium text-gray-700">
                            <option value="antri_dokter" {{ request('status') == 'antri_dokter' ? 'selected' : '' }}>Antri Dokter (Siap Periksa)</option>
                            <option value="kasir" {{ request('status') == 'kasir' ? 'selected' : '' }}>Sudah Diperiksa (Menunggu Kasir)</option>
                        </select>
                        <div class="relative w-full sm:w-80">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pasien / No. RM..." class="w-full pr-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap shadow-sm shadow-hfc-primary/20 inline-flex items-center">
                            <span>Cari</span>
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('rekam-medis.index') }}" class="px-4 py-2.5 bg-white border border-rose-200 hover:border-rose-400 hover:bg-rose-50 text-rose-600 font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-base text-rose-500">restart_alt</span>
                                <span>Reset</span>
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Tabel Antrian Dokter -->
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-900 font-bold border-b border-gray-200">
                                <th class="p-4">No. Antrian</th>
                                <th class="p-4">Nama Pasien</th>
                                <th class="p-4">Hasil Periksa Awal</th>
                                <th class="p-4">Diagnosa Dokter</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Aksi (Periksa Dokter)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($kunjungans as $k)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 font-mono font-bold text-hfc-primary text-base">
                                        {{ $k->no_antrian }}
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800">{{ $k->pasien->nama ?? '-' }}</div>
                                        <div class="text-xs font-mono text-gray-400">RM: {{ $k->pasien->no_rm ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 text-xs text-gray-600">
                                        @if($k->rekamMedis)
                                            <div>Suhu: <span class="font-bold text-gray-800">{{ $k->rekamMedis->suhu ? $k->rekamMedis->suhu.'°C' : '-' }}</span></div>
                                            <div>TD: <span class="font-bold text-gray-800">{{ $k->rekamMedis->tekanan_darah ?? '-' }}</span></div>
                                            <div>BB: <span class="font-bold text-gray-800">{{ $k->rekamMedis->berat_badan ? $k->rekamMedis->berat_badan.' kg' : '-' }}</span></div>
                                        @else
                                            <span class="text-gray-400 italic">Belum diperiksa perawat</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-700">
                                        {{ $k->rekamMedis->diagnosa ?? '-' }}
                                    </td>
                                    <td class="p-4">
                                        @if($k->status == 'antri_dokter')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                Menunggu Periksa Dokter
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                Sudah Diperiksa (Kasir)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('rekam-medis.create', $k->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-hfc-primary hover:bg-hfc-hover text-white text-xs font-bold rounded-xl transition shadow-sm">
                                            Input Rekam Medis
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400 text-sm">
                                        Belum ada antrian pasien untuk pemeriksaan Dokter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $kunjungans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
