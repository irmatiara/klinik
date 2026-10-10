<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Stasiun Perawat: Pemeriksaan Awal Pasien') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Flash Message -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Banner Penjelasan -->
            <div class="bg-white rounded-2xl p-6 border border-hfc-primary/20 shadow-lg shadow-hfc-primary/5 flex items-center justify-between relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-hfc-primary/5 rounded-full blur-2xl pointer-events-none"></div>
                <div>
                    <h3 class="text-2xl font-bold text-hfc-dark mt-1">Pemeriksaan Suhu, Tekanan Darah & Berat Badan</h3>
                    <p class="text-gray-600 text-sm mt-1">Dipanggil oleh Perawat sebelum pasien masuk ke ruang periksa Dokter.</p>
                </div>
            </div>

            <!-- Card Filter & Search -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6 flex justify-end">
                    <form method="GET" action="{{ route('pemeriksaan-awal.index') }}" class="flex flex-col sm:flex-row items-center justify-end gap-3 w-full sm:w-auto">
                        <select name="status" class="w-full sm:w-auto text-sm border-gray-200 rounded-xl focus:border-hfc-primary focus:ring-hfc-primary py-2.5 px-3 font-medium text-gray-700">
                            <option value="antri_triage" {{ request('status') == 'antri_triage' ? 'selected' : '' }}>Antri Pemeriksaan Awal</option>
                            <option value="antri_dokter" {{ request('status') == 'antri_dokter' ? 'selected' : '' }}>Sudah Periksa Awal (Antri Dokter)</option>
                        </select>
                        <div class="relative w-full sm:w-80">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pasien / No. RM..." class="w-full pl-10 pr-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap shadow-sm shadow-hfc-primary/20">
                            Cari
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('pemeriksaan-awal.index') }}" class="px-4 py-2.5 bg-white border border-rose-200 hover:border-rose-400 hover:bg-rose-50 text-rose-600 font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap inline-flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span>Reset</span>
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Tabel Antrian Pemeriksaan Awal -->
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-900 font-bold border-b border-gray-200">
                                <th class="p-4">No. Antrian</th>
                                <th class="p-4">Nama Pasien / No. RM</th>
                                <th class="p-4">Suhu (°C)</th>
                                <th class="p-4">Tekanan Darah</th>
                                <th class="p-4">Berat Badan</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Aksi</th>
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
                                    <td class="p-4 font-medium text-gray-700">
                                        {{ optional($k->rekamMedis)->suhu ? optional($k->rekamMedis)->suhu . ' °C' : '-' }}
                                    </td>
                                    <td class="p-4 font-medium text-gray-700">
                                        {{ optional($k->rekamMedis)->tekanan_darah ?? '-' }}
                                    </td>
                                    <td class="p-4 font-medium text-gray-700">
                                        {{ optional($k->rekamMedis)->berat_badan ? optional($k->rekamMedis)->berat_badan . ' kg' : '-' }}
                                    </td>
                                    <td class="p-4">
                                        @if($k->status == 'antri_triage')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Menunggu Pemeriksaan Awal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200">
                                                Sudah Periksa Awal (Antri Dokter)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('pemeriksaan-awal.create', $k->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-hfc-primary hover:bg-hfc-hover text-white text-xs font-bold rounded-xl transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Input / Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400 text-sm">
                                        Belum ada antrian pasien untuk pemeriksaan awal saat ini.
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
