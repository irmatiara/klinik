<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Banner Selamat Datang & Quick Actions (Bulletproof Inline Style + High Contrast) -->
            <div class="rounded-2xl p-6 sm:p-7 shadow-xl relative overflow-hidden"
                style="background: linear-gradient(135deg, #7c1679 0%, #b224ae 50%, #4a0b49 100%); color: #ffffff;">
                <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-white/15 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-black mt-2.5 tracking-tight" style="color: #ffffff;">
                            Selamat Datang, <span style="color: #ffea7a; font-weight: 900;">{{ Auth::user()->name ?? 'Administrator Klinik' }}</span>
                        </h3>
                        <p class="text-sm mt-2 font-semibold max-w-2xl leading-relaxed" style="color: rgba(255, 255, 255, 0.95);">
                            Pantau statistik pelayanan klinik, alur antrian pasien harian, hingga ketersediaan stok obat secara real-time.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        @if(Auth::user()->hasRole(['resepsionis', 'perawat']))
                        <a href="{{ route('kunjungan.create') }}"
                            class="px-5 py-3 font-black text-sm rounded-xl transition shadow-lg flex items-center gap-2 hover:opacity-90"
                            style="background-color: #ffffff; color: #b224ae;">
                            <span>Ambil Antrian Baru</span>
                        </a>
                        @endif

                        @if(Auth::user()->hasRole(['resepsionis', 'perawat', 'dokter']))
                        <a href="{{ route('pasien.create') }}"
                            class="px-4 py-3 font-bold text-sm rounded-xl transition shadow-md flex items-center gap-2 hover:bg-black/40"
                            style="background-color: rgba(0,0,0,0.25); color: #ffffff; border: 1px solid rgba(255,255,255,0.4);">
                            <span>Pasien Baru</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ringkasan Statistik Card Utama (4 Kartu) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card Total Pasien -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pasien Terdaftar</p>
                            <h4 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($totalPasien) }}</h4>
                        </div>
                    </div>
                    <div class="border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                        <span>Master data pasien</span>
                        <a href="{{ route('pasien.index') }}" class="font-bold text-hfc-primary hover:underline">Lihat Semua →</a>
                    </div>
                </div>

                <!-- Card Antrian Hari Ini -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Antrian Pasien Hari Ini</p>
                            <h4 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($antrianHariIni) }}</h4>
                        </div>
                    </div>
                    <div class="border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                        <span>Total pendaftaran</span>
                        <a href="{{ route('kunjungan.index') }}" class="font-bold text-blue-600 hover:underline">Lihat Antrian →</a>
                    </div>
                </div>

                <!-- Card Pasien Selesai Hari Ini -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelayanan Selesai</p>
                            <h4 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($pasienSelesaiHariIni) }}</h4>
                        </div>
                    </div>
                    <div class="border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                        <span>Tuntas obat diserahkan</span>
                        <span class="font-bold text-emerald-600">Hari Ini</span>
                    </div>
                </div>

                <!-- Card Pendapatan Lunas Hari Ini -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pendapatan Kasir</p>
                            <h4 class="text-xl font-extrabold text-gray-900 mt-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                        <span>Total pembayaran lunas</span>
                        <a href="{{ route('tagihan.index') }}" class="font-bold text-amber-600 hover:underline">Detail Kasir →</a>
                    </div>
                </div>
            </div>

            <!-- Stasiun Alur Pelayanan Real-Time (Status Pasien Saat Ini) -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-hfc-dark">Status Antrian Aktif per Stasiun Pelayanan</h3>
                        <p class="text-xs text-gray-500">Jumlah pasien yang sedang menunggu di tiap stasiun hari ini</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Stasiun 1: Triage / Perawat -->
                    <a href="{{ route('pemeriksaan-awal.index') }}" class="p-4 rounded-xl border border-sky-100 bg-sky-50/50 hover:bg-sky-50 transition block">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-sky-800">Pemeriksaan Awal</span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-sky-900">{{ $stasiunTriage }}</span>
                            <span class="text-xs text-sky-700">Pasien Menunggu</span>
                        </div>
                    </a>

                    <!-- Stasiun 2: Dokter -->
                    <a href="{{ route('rekam-medis.index') }}" class="p-4 rounded-xl border border-indigo-100 bg-indigo-50/50 hover:bg-indigo-50 transition block">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-indigo-800">Ruang Dokter</span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-indigo-900">{{ $stasiunDokter }}</span>
                            <span class="text-xs text-indigo-700">Pasien Diperiksa</span>
                        </div>
                    </a>

                    <!-- Stasiun 3: Kasir -->
                    <a href="{{ route('tagihan.index') }}" class="p-4 rounded-xl border border-amber-100 bg-amber-50/50 hover:bg-amber-50 transition block">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-amber-800">Kasir / Pembayaran</span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-amber-900">{{ $stasiunKasir }}</span>
                            <span class="text-xs text-amber-700">Pasien Belum Bayar</span>
                        </div>
                    </a>

                    <!-- Stasiun 4: Farmasi -->
                    <a href="{{ route('resep-obat.index') }}" class="p-4 rounded-xl border border-purple-100 bg-purple-50/50 hover:bg-purple-50 transition block">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-purple-800">Farmasi / Apotek</span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-purple-900">{{ $stasiunApotek }}</span>
                            <span class="text-xs text-purple-700">Menunggu Obat</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Grid 2 Kolom: Tabel Antrian Terbaru & Alert Stok Obat -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri: Tabel Antrian Pasien Terbaru Hari Ini (2/3 Lebar) -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-hfc-dark">Daftar Antrian Kunjungan Hari Ini</h3>
                            <p class="text-xs text-gray-500">Pasien yang terdaftar pada tanggal {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                        </div>
                        <a href="{{ route('kunjungan.index') }}" class="text-xs font-bold text-hfc-primary hover:underline">Kelola Semua →</a>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-gray-900 font-bold border-b border-gray-200">
                                    <th class="p-3.5">No. Antrian</th>
                                    <th class="p-3.5">Nama Pasien / RM</th>
                                    <th class="p-3.5">Status Alur</th>
                                    <th class="p-3.5 text-center">Status Bayar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse($antrianTerbaru as $k)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-3.5 font-mono font-bold text-hfc-primary text-sm">
                                        {{ $k->no_antrian }}
                                    </td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-gray-900">{{ $k->pasien->nama ?? '-' }}</div>
                                        <div class="text-[11px] font-mono text-gray-400">RM: {{ $k->pasien->no_rm ?? '-' }}</div>
                                    </td>
                                    <td class="p-3.5">
                                        @if($k->status == 'antri_triage')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-200">1. Antri Triage</span>
                                        @elseif($k->status == 'antri_dokter' || $k->status == 'periksa')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">2. Antri Dokter</span>
                                        @elseif($k->status == 'kasir')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">3. Antri Kasir</span>
                                        @elseif($k->status == 'apotek')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">4. Antri Farmasi</span>
                                        @elseif($k->status == 'selesai')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">✓ Selesai</span>
                                        @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center">
                                        @if(($k->tagihan->status_bayar ?? '') == 'lunas')
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">Lunas</span>
                                        @else
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-200">Belum Bayar</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-400 text-xs">
                                        Belum ada antrian pasien terdaftar hari ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kolom Kanan: Peringatan Stok Obat Menipis (1/3 Lebar) -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-hfc-dark flex items-center gap-1.5">
                                Stok Obat Menipis
                            </h3>
                            <p class="text-xs text-gray-500">Obat dengan sisa stok ≤ 10 unit</p>
                        </div>
                        <a href="{{ route('obat.index') }}" class="text-xs font-bold text-hfc-primary hover:underline">Kelola Stok →</a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($obatStokMenipis as $obat)
                        <div class="p-3 bg-rose-50/60 rounded-xl border border-rose-100 flex items-center justify-between">
                            <div>
                                <h5 class="text-xs font-bold text-gray-900">{{ $obat->nama_obat }}</h5>
                                <span class="text-[11px] font-mono text-gray-500">{{ $obat->kode_obat }}</span>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 bg-rose-600 text-white font-extrabold text-xs rounded-lg shadow-sm block">
                                    {{ $obat->stok }} Qty
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-emerald-600 bg-emerald-50/50 rounded-xl border border-emerald-100 text-xs font-semibold">
                            ✓ Seluruh stok obat terpantau aman dan cukup.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>