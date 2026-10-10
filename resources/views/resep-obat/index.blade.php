<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Farmasi & Penyerahan Resep Obat') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Flash Message -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl p-6 border border-hfc-primary/20 shadow-lg shadow-hfc-primary/5 flex items-center justify-between relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-hfc-primary/5 rounded-full blur-2xl pointer-events-none"></div>
                <div>
                    <h3 class="text-2xl font-bold text-hfc-dark mt-1">Stasiun Farmasi & Penyerahan Obat</h3>
                    <p class="text-gray-600 text-sm mt-1">Apoteker menyiapkan obat berdasarkan resep dokter, menyerahkan ke pasien, dan memotong stok otomatis.</p>
                </div>
            </div>

            <!-- Card Utama -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <!-- Header Card (Judul & Subjudul) -->
                <div class="border-b border-gray-100 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-hfc-dark">Daftar Antrian Resep Obat Pasien</h3>
                    <p class="text-sm text-gray-500">Pasien yang telah melunasi pembayaran di kasir dan siap mengambil obat</p>
                </div>

                <!-- Baris Pencarian & Filter (Atas-Bawah / Stacked) -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('resep-obat.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                        <select name="status" class="text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary py-2.5 px-3 w-full sm:w-auto font-medium text-gray-700">
                            <option value="apotek" {{ request('status') == 'apotek' ? 'selected' : '' }}>Antri Apotek (Menunggu Obat)</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai (Sudah Ambil Obat)</option>
                        </select>
                        <div class="relative w-full sm:w-80">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pasien / No. RM..." class="w-full pl-10 pr-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap shadow-sm shadow-hfc-primary/20">
                            Filter
                        </button>
                        @if(request('search') || request('status'))
                        <a href="{{ route('resep-obat.index') }}" class="px-4 py-2.5 bg-white border border-rose-200 hover:border-rose-400 hover:bg-rose-50 text-rose-600 font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap inline-flex items-center gap-1.5 shadow-sm">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset</span>
                        </a>
                        @endif
                    </form>
                </div>

                <!-- Tabel Antrian Resep Obat -->
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 font-semibold border-b border-gray-100">
                                <th class="p-4">No. Antrian</th>
                                <th class="p-4">Nama Pasien / No. RM</th>
                                <th class="p-4">Rincian Resep Obat (Dokter)</th>
                                <th class="p-4">Status Pembayaran</th>
                                <th class="p-4">Status Pelayanan</th>
                                <th class="p-4 text-center">Aksi (Penyerahan Obat)</th>
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
                                <td class="p-4">
                                    <ul class="space-y-1 text-xs">
                                        @forelse($k->resepObats as $resep)
                                        <li class="bg-gray-100/70 px-2.5 py-1.5 rounded-lg border border-gray-200">
                                            <span class="font-bold text-gray-800">{{ $resep->obat->nama_obat ?? 'Obat' }}</span>
                                            <span class="text-hfc-primary font-bold">({{ $resep->jumlah }} Qty)</span>
                                            <span class="text-gray-500 block italic">Aturan: {{ $resep->aturan_pakai }}</span>
                                        </li>
                                        @empty
                                        <span class="text-gray-400 italic">Tidak ada resep obat</span>
                                        @endforelse
                                    </ul>
                                </td>
                                <td class="p-4">
                                    @if(($k->tagihan->status_bayar ?? '') == 'lunas')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        ✓ LUNAS (Rp {{ number_format($k->tagihan->total_tagihan ?? 0, 0, ',', '.') }})
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                        BELUM BAYAR
                                    </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($k->status == 'apotek')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-600 border border-purple-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                        Menunggu Penyerahan Obat
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        SELESAI
                                    </span>
                                    @endif
                                </td>
                                <td class="p-4 text-center" x-data="{ showModal: false }">
                                    @if($k->status == 'apotek')
                                    <button type="button" @click="showModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                        Serahkan Obat & Selesai
                                    </button>

                                    <!-- Modal Popup Konfirmasi Penyerahan Obat -->
                                    <div x-show="showModal"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50">

                                        <div @click.outside="showModal = false"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="bg-white rounded-2xl p-5 sm:p-6 text-left border border-gray-100 transform transition-all space-y-4"
                                            style="max-width: 440px; width: 100%; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(0, 0, 0, 0.08);">

                                            <!-- Modal Header -->
                                            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                                <h3 class="text-base font-bold text-hfc-dark flex items-center gap-2">
                                                    Konfirmasi Penyerahan
                                                </h3>
                                                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1 text-sm font-bold rounded-lg hover:bg-gray-100 transition">✕</button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="space-y-3 text-xs">
                                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-200/80 space-y-1.5">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-500">Nama Pasien:</span>
                                                        <span class="font-bold text-gray-900 text-sm">{{ $k->pasien->nama ?? '-' }} ({{ $k->no_antrian }})</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-500">No. Rekam Medis:</span>
                                                        <span class="font-mono font-bold text-hfc-primary">{{ $k->pasien->no_rm ?? '-' }}</span>
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="font-bold text-gray-700 block mb-1.5">Rincian Obat Diserahkan:</span>
                                                    <ul class="space-y-1.5 bg-purple-50/60 p-3 rounded-xl border border-purple-100">
                                                        @foreach($k->resepObats as $resep)
                                                        <li class="flex justify-between items-center text-gray-800 text-xs">
                                                            <span>• {{ $resep->obat->nama_obat ?? 'Obat' }}</span>
                                                            <span class="font-bold text-hfc-primary px-2 py-0.5 bg-white rounded border border-purple-200 text-[11px]">{{ $resep->jumlah }} Qty</span>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div class="text-[11px] text-amber-800 bg-amber-50 p-2.5 rounded-xl border border-amber-200/80 flex items-start gap-2">
                                                    <span>Stok obat akan terpotong otomatis & status alur pasien <strong>SELESAI</strong>.</span>
                                                </div>
                                            </div>

                                            <!-- Modal Footer Actions -->
                                            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                                                <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                                                    Batal
                                                </button>
                                                <form method="POST" action="{{ route('resep-obat.serahkan', $k->id) }}">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                        Ya, Serahkan Obat
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                    @else
                                    <span class="text-xs font-bold text-gray-400">✓ Obat Sudah Diserahkan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400 text-sm">
                                    Belum ada antrian resep obat di Farmasi saat ini.
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