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
                    <a href="{{ route('kunjungan.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white text-sm font-semibold rounded-xl transition shadow-md shadow-hfc-primary/20 whitespace-nowrap">
                        + Ambil Nomor Antrian
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
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pasien / No. RM..." class="w-full pl-10 pr-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap shadow-sm shadow-hfc-primary/20">
                            Filter
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('kunjungan.index') }}" class="px-4 py-2.5 bg-white border border-rose-200 hover:border-rose-400 hover:bg-rose-50 text-rose-600 font-semibold text-sm rounded-xl transition shrink-0 whitespace-nowrap inline-flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
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
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('kunjungan.edit', $kunjungan->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition inline-flex items-center justify-center" title="Edit Antrian">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('kunjungan.destroy', $kunjungan->id) }}" onsubmit="return confirm('Yakin ingin menghapus antrian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition inline-flex items-center justify-center" title="Hapus Antrian">
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
