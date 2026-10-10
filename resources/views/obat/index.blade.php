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
                        <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
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
                    <a href="{{ route('obat.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white text-sm font-semibold rounded-xl transition shadow-md shadow-hfc-primary/20 whitespace-nowrap gap-1">
                        <span class="material-symbols-outlined text-base">add</span>
                        <span>Tambah Obat Baru</span>
                    </a>
                </div>

                <!-- Baris Pencarian (Atas-Bawah / Stacked) -->
                <div class="mb-6 flex justify-end">
                    <form method="GET" action="{{ route('obat.index') }}" class="flex flex-row items-center justify-end gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-80">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode / Nama Obat..." class="w-full pr-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap shadow-sm shadow-hfc-primary/20 inline-flex items-center">
                            <span>Cari</span>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('obat.index') }}" class="px-4 py-2.5 bg-white border border-rose-200 hover:border-rose-400 hover:bg-rose-50 text-rose-600 font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-base text-rose-500">restart_alt</span>
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
                                    <td class="p-4 text-center" x-data="{ showDeleteModal: false }">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('obat.edit', $obat->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition inline-flex items-center justify-center" title="Edit Obat">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>

                                            <button type="button" @click="showDeleteModal = true" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition inline-flex items-center justify-center" title="Hapus Obat">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>

                                            <!-- Modal Popup Konfirmasi Hapus Obat -->
                                            <div x-show="showDeleteModal"
                                                x-cloak
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50">

                                                <div @click.outside="showDeleteModal = false"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    class="bg-white rounded-2xl p-5 sm:p-6 text-left border border-gray-100 transform transition-all space-y-4"
                                                    style="max-width: 420px; width: 100%; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(0, 0, 0, 0.08);">

                                                    <!-- Modal Header -->
                                                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                                        <h3 class="text-base font-bold text-hfc-dark flex items-center gap-2">
                                                            <span class="material-symbols-outlined text-rose-500 text-xl">warning</span>
                                                            Konfirmasi Hapus Obat
                                                        </h3>
                                                        <button type="button" @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-600 p-1 text-sm font-bold rounded-lg hover:bg-gray-100 transition">✕</button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="space-y-3 text-xs">
                                                        <p class="text-gray-600">Apakah Anda yakin ingin menghapus data obat berikut dari Master Obat?</p>
                                                        <div class="bg-rose-50/60 p-3 rounded-xl border border-rose-100 space-y-1.5 text-left">
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-gray-500">Nama Obat:</span>
                                                                <span class="font-bold text-gray-900 text-sm">{{ $obat->nama_obat }}</span>
                                                            </div>
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-gray-500">Kode Obat:</span>
                                                                <span class="font-mono font-bold text-hfc-primary text-sm">{{ $obat->kode_obat }}</span>
                                                            </div>
                                                        </div>
                                                        <p class="text-[11px] text-rose-600 font-semibold">Tindakan ini tidak dapat dibatalkan!</p>
                                                    </div>

                                                    <!-- Modal Footer Actions -->
                                                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                                                        <button type="button" @click="showDeleteModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                                                            Batal
                                                        </button>
                                                        <form method="POST" action="{{ route('obat.destroy', $obat->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                                Ya, Hapus Obat
                                                            </button>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
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
