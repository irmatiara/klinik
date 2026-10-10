<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Master Data Obat & Farmasi') }}
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

            <!-- Main Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <!-- Header Card (Judul & Tombol Tambah) -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-hfc-dark">Daftar Obat Klinik</h3>
                        <p class="text-sm text-gray-500">Kelola daftar obat, stok, dan harga untuk resep dokter & farmasi</p>
                    </div>
                    <a href="{{ route('obat.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white text-sm font-semibold rounded-xl transition shadow-md shadow-hfc-primary/20 whitespace-nowrap">
                        + Tambah Obat Baru
                    </a>
                </div>

                <!-- Baris Pencarian (Atas-Bawah / Stacked) -->
                <div class="mb-6 flex justify-end">
                    <form method="GET" action="{{ route('obat.index') }}" class="flex flex-row items-center justify-end gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-80">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode / Nama Obat..." class="w-full pl-10 pr-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap shadow-sm shadow-hfc-primary/20">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('obat.index') }}" class="px-4 py-2.5 bg-white border border-rose-200 hover:border-rose-400 hover:bg-rose-50 text-rose-600 font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap inline-flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span>Reset</span>
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Tabel Master Obat -->
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-900 font-bold border-b border-gray-200">
                                <th class="p-4">Kode Obat</th>
                                <th class="p-4">Nama Obat</th>
                                <th class="p-4">Harga Satuan</th>
                                <th class="p-4">Stok Obat</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($obats as $obat)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 font-mono font-bold text-hfc-primary">
                                        {{ $obat->kode_obat }}
                                    </td>
                                    <td class="p-4 font-bold text-gray-800">
                                        {{ $obat->nama_obat }}
                                    </td>
                                    <td class="p-4 font-medium text-gray-700">
                                        Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4">
                                        @if($obat->stok <= 10)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                                Stok Menipis: {{ $obat->stok }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                Tersedia: {{ $obat->stok }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('obat.edit', $obat->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition inline-flex items-center justify-center" title="Edit Obat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('obat.destroy', $obat->id) }}" onsubmit="return confirm('Yakin ingin menghapus obat {{ $obat->nama_obat }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition inline-flex items-center justify-center" title="Hapus Obat">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400">Belum ada master data obat. Silakan tambah data baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $obats->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
