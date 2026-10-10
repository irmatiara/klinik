<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Pendaftaran & Antrian Pasien') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm text-emerald-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <!-- Header Card (Judul & Tombol Tambah) -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-hfc-dark">Daftar Antrian Pelayanan Klinik</h3>
                        <p class="text-sm text-gray-500">Kelola nomor antrian dan alur pelayanan pasien</p>
                    </div>
                    <a href="{{ route('kunjungan.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white text-sm font-semibold rounded-xl transition shadow-md shadow-hfc-primary/20 whitespace-nowrap gap-1">
                        <span>Ambil Nomor Antrian</span>
                    </a>
                </div>

                <!-- Baris Pencarian & Filter (Atas-Bawah / Stacked) -->
                <div class="mb-6 flex justify-end">
                    <form method="GET" action="{{ route('kunjungan.index') }}" class="flex flex-col sm:flex-row items-center justify-end gap-3 w-full sm:w-auto">
                        <select name="status" class="text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary py-2.5 px-3 w-full sm:w-auto font-medium text-gray-700">
                            <option value="">Semua Status Antrian</option>
                            <option value="antri_triage" {{ request('status') == 'antri_triage' ? 'selected' : '' }}>Pemeriksaan Awal (Perawat)</option>
                            <option value="antri_dokter" {{ request('status') == 'antri_dokter' ? 'selected' : '' }}>Antri Dokter</option>
                            <option value="periksa" {{ request('status') == 'periksa' ? 'selected' : '' }}>Pemeriksaan Dokter</option>
                            <option value="kasir" {{ request('status') == 'kasir' ? 'selected' : '' }}>Kasir / Pembayaran</option>
                            <option value="apotek" {{ request('status') == 'apotek' ? 'selected' : '' }}>Apotek / Farmasi</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <div class="relative w-full sm:w-80">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pasien / No. RM..." class="w-full pr-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap shadow-sm shadow-hfc-primary/20 inline-flex items-center gap-1.5">
                            <span>Filter</span>
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('kunjungan.index') }}" class="px-4 py-2.5 bg-white border border-rose-200 hover:border-rose-400 hover:bg-rose-50 text-rose-600 font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-base text-rose-500">restart_alt</span>
                                <span>Reset</span>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-900 text-xs uppercase tracking-wider font-bold border-b border-gray-200">
                                <th class="p-4">No. Antrian</th>
                                <th class="p-4">Nama Pasien</th>
                                <th class="p-4">No. RM</th>
                                <th class="p-4">Waktu Daftar</th>
                                <th class="p-4">Status Alur Pelayanan</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($kunjungans as $kunjungan)
                                <tr class="hover:bg-hfc-light/30 transition">
                                    <td class="p-4 font-black text-xl text-hfc-primary font-mono">
                                        {{ $kunjungan->no_antrian ?? 'A-00'.$kunjungan->id }}
                                    </td>
                                    <td class="p-4 font-bold text-hfc-dark">
                                        {{ $kunjungan->pasien->nama ?? '-' }}
                                    </td>
                                    <td class="p-4 text-gray-600 font-mono text-xs">{{ $kunjungan->pasien->no_rm ?? '-' }}</td>
                                    <td class="p-4 text-gray-600 text-xs">{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y H:i') }}</td>
                                    <td class="p-4">
                                        @php
                                            $statusBadge = [
                                                'antri_triage' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'antri_dokter' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'periksa' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                'kasir' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                'apotek' => 'bg-teal-100 text-teal-800 border-teal-200',
                                                'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'batal' => 'bg-rose-100 text-rose-800 border-rose-200',
                                            ];

                                            $statusLabel = [
                                                'antri_triage' => 'Cek Vital (Perawat)',
                                                'antri_dokter' => 'Menunggu Dokter',
                                                'periksa' => 'Pemeriksaan Dokter',
                                                'kasir' => 'Kasir / Pembayaran',
                                                'apotek' => 'Apotek / Obat',
                                                'selesai' => 'Selesai',
                                                'batal' => 'Dibatalkan',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $statusBadge[$kunjungan->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabel[$kunjungan->status] ?? strtoupper($kunjungan->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center" x-data="{ showDeleteModal: false }">
                                        @if($kunjungan->status !== 'selesai')
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('kunjungan.edit', $kunjungan->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition inline-flex items-center justify-center" title="Edit Antrian">
                                                    <span class="material-symbols-outlined text-lg">edit</span>
                                                </a>

                                                <button type="button" @click="showDeleteModal = true" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition inline-flex items-center justify-center" title="Hapus Antrian">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>

                                                <!-- Modal Popup Konfirmasi Hapus Antrian -->
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
                                                                Konfirmasi Hapus Antrian
                                                            </h3>
                                                            <button type="button" @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-600 p-1 text-sm font-bold rounded-lg hover:bg-gray-100 transition">✕</button>
                                                        </div>

                                                        <!-- Modal Body -->
                                                        <div class="space-y-3 text-xs">
                                                            <p class="text-gray-600">Apakah Anda yakin ingin menghapus data antrian berikut?</p>
                                                            <div class="bg-rose-50/60 p-3 rounded-xl border border-rose-100 space-y-1.5 text-left">
                                                                <div class="flex justify-between items-center">
                                                                    <span class="text-gray-500">Nama Pasien:</span>
                                                                    <span class="font-bold text-gray-900 text-sm">{{ $kunjungan->pasien->nama ?? '-' }}</span>
                                                                </div>
                                                                <div class="flex justify-between items-center">
                                                                    <span class="text-gray-500">No. Antrian:</span>
                                                                    <span class="font-mono font-bold text-hfc-primary text-sm">{{ $kunjungan->no_antrian ?? 'A-00'.$kunjungan->id }}</span>
                                                                </div>
                                                                <div class="flex justify-between items-center">
                                                                    <span class="text-gray-500">No. RM:</span>
                                                                    <span class="font-mono text-gray-700">{{ $kunjungan->pasien->no_rm ?? '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <p class="text-[11px] text-rose-600 font-semibold">Tindakan ini tidak dapat dibatalkan!</p>
                                                        </div>

                                                        <!-- Modal Footer Actions -->
                                                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                                                            <button type="button" @click="showDeleteModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                                                                Batal
                                                            </button>
                                                            <form method="POST" action="{{ route('kunjungan.destroy', $kunjungan->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                                    Ya, Hapus Antrian
                                                                </button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 font-medium">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400">Belum ada antrian pendaftaran hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($kunjungans, 'links'))
                    <div class="mt-6">
                        {{ $kunjungans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
