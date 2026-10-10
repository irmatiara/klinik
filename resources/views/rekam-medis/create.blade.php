<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Form Pemeriksaan Dokter & Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Ringkasan Pasien & Hasil Pemeriksaan Awal (Suhu, TD, BB) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <span class="text-xs font-bold text-hfc-primary uppercase tracking-wider block">Pasien Dalam Pemeriksaan</span>
                        <h3 class="text-2xl font-bold text-hfc-dark mt-0.5">{{ $kunjungan->pasien->nama ?? '-' }}</h3>
                        <p class="text-xs text-gray-500">No. RM: <span class="font-mono font-bold">{{ $kunjungan->pasien->no_rm ?? '-' }}</span> | Gender: {{ ($kunjungan->pasien->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }} | Telepon: {{ $kunjungan->pasien->no_telp ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Nomor Antrian</span>
                        <div class="text-3xl font-black text-hfc-primary font-mono">
                            {{ $kunjungan->no_antrian }}
                        </div>
                    </div>
                </div>

                <!-- Card Parameter Vital Sign dari Perawat -->
                <div class="bg-purple-50/60 p-4 rounded-xl border border-purple-100">
                    <span class="text-xs font-bold text-purple-900 uppercase tracking-wider block mb-2 flex items-center gap-1">
                        <span>Hasil Pemeriksaan Awal (Oleh Perawat)</span>
                    </span>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-white p-3 rounded-xl border border-purple-100 shadow-sm">
                            <span class="text-xs text-gray-400 font-semibold block">Suhu Tubuh</span>
                            <span class="text-lg font-bold text-hfc-dark">{{ optional($kunjungan->rekamMedis)->suhu ? optional($kunjungan->rekamMedis)->suhu . ' °C' : '-' }}</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-purple-100 shadow-sm">
                            <span class="text-xs text-gray-400 font-semibold block">Tekanan Darah</span>
                            <span class="text-lg font-bold text-hfc-dark">{{ optional($kunjungan->rekamMedis)->tekanan_darah ?? '-' }}</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-purple-100 shadow-sm">
                            <span class="text-xs text-gray-400 font-semibold block">Berat Badan</span>
                            <span class="text-lg font-bold text-hfc-dark">{{ optional($kunjungan->rekamMedis)->berat_badan ? optional($kunjungan->rekamMedis)->berat_badan . ' kg' : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pemeriksaan Dokter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded-xl">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('rekam-medis.store', $kunjungan->id) }}" class="space-y-6">
                    @csrf

                    <!-- 1. Keluhan Utama -->
                    <div>
                        <x-input-label for="keluhan" :value="__('Keluhan Utama Pasien')" />
                        <textarea id="keluhan" name="keluhan" rows="3" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm text-sm" required placeholder="Contoh: Demam sejak 2 hari yang lalu, pusing, dan batuk kering...">{{ old('keluhan', optional($kunjungan->rekamMedis)->keluhan) }}</textarea>
                    </div>

                    <!-- 2. Diagnosa Dokter -->
                    <div>
                        <x-input-label for="diagnosa" :value="__('Diagnosa Dokter')" />
                        <textarea id="diagnosa" name="diagnosa" rows="3" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm text-sm" required placeholder="Contoh: ISPA (Infeksi Saluran Pernapasan Akut), Febris E.C Suspek Virus...">{{ old('diagnosa', optional($kunjungan->rekamMedis)->diagnosa) }}</textarea>
                    </div>

                    <!-- 3. Resep Obat Dokter (Dynamic Row) -->
                    <div class="border-t border-gray-100 pt-5">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                            <div>
                                <h4 class="text-md text-hfc-dark flex items-center gap-1">
                                    <span>Resep Obat Pasien</span>
                                </h4>
                                <p class="text-xs text-gray-500">Pilih obat dari Master Obat dan atur jumlah serta aturan pakainya.</p>
                            </div>
                            <button type="button" id="btn-add-obat" class="px-4 py-2 bg-hfc-light hover:bg-purple-100 text-hfc-primary font-bold text-xs rounded-xl transition border border-hfc-primary/20 flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-base">add</span>
                                <span>Tambah Baris Obat</span>
                            </button>
                        </div>

                        <!-- Header Kolom Resep Obat -->
                        <div class="flex items-center gap-3 px-3 py-2 bg-gray-100/70 rounded-xl text-xs font-bold text-gray-600 mb-2">
                            <div class="flex-1">Pilih Nama Obat (Harga & Stok)</div>
                            <div class="w-24 text-center">Jumlah</div>
                            <div class="w-64">Aturan Pakai</div>
                            <div class="w-10 text-center">Aksi</div>
                        </div>

                        <div id="wrapper-resep-obat" class="space-y-3">
                            @forelse($kunjungan->resepObats as $index => $resep)
                                <div class="row-obat flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <select name="obat_id[]" class="w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl text-sm py-2.5 px-3 font-medium text-gray-800">
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach($obats as $obat)
                                                <option value="{{ $obat->id }}" {{ $resep->obat_id == $obat->id ? 'selected' : '' }}>
                                                    [{{ $obat->kode_obat }}] {{ $obat->nama_obat }} — Rp {{ number_format($obat->harga, 0, ',', '.') }} (Stok: {{ $obat->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24 shrink-0">
                                        <input type="number" min="1" name="jumlah[]" value="{{ $resep->jumlah }}" placeholder="Qty" class="w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl text-sm py-2.5 px-2 text-center font-bold text-gray-800" />
                                    </div>
                                    <div class="w-64 shrink-0">
                                        <input type="text" name="aturan_pakai[]" value="{{ $resep->aturan_pakai }}" placeholder="Aturan Pakai (misal 3x1)" class="w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl text-sm py-2.5 px-3" />
                                    </div>
                                    <div class="w-10 shrink-0 text-center">
                                        <button type="button" class="btn-remove-row p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl text-sm font-bold transition" title="Hapus Obat">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="row-obat flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <select name="obat_id[]" class="w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl text-sm py-2.5 px-3 font-medium text-gray-800">
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach($obats as $obat)
                                                <option value="{{ $obat->id }}">
                                                    [{{ $obat->kode_obat }}] {{ $obat->nama_obat }} — Rp {{ number_format($obat->harga, 0, ',', '.') }} (Stok: {{ $obat->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24 shrink-0">
                                        <input type="number" min="1" name="jumlah[]" value="1" placeholder="Qty" class="w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl text-sm py-2.5 px-2 text-center font-bold text-gray-800" />
                                    </div>
                                    <div class="w-64 shrink-0">
                                        <input type="text" name="aturan_pakai[]" value="3x1 Sehari Sesudah Makan" placeholder="Aturan Pakai (misal 3x1)" class="w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl text-sm py-2.5 px-3" />
                                    </div>
                                    <div class="w-10 shrink-0 text-center">
                                        <button type="button" class="btn-remove-row p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl text-sm font-bold transition" title="Hapus Obat">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('rekam-medis.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition inline-flex items-center justify-center shrink-0">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Rekam Medis & Oper ke Kasir') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Script Tambah / Hapus Baris Obat secara Dynamic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('wrapper-resep-obat');
            const btnAdd = document.getElementById('btn-add-obat');

            btnAdd.addEventListener('click', function() {
                const firstRow = wrapper.querySelector('.row-obat');
                if (firstRow) {
                    const newRow = firstRow.cloneNode(true);
                    // Reset input values untuk baris baru
                    newRow.querySelector('select').value = '';
                    newRow.querySelector('input[type="number"]').value = '1';
                    newRow.querySelector('input[type="text"]').value = '3x1 Sehari Sesudah Makan';
                    wrapper.appendChild(newRow);
                }
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target && e.target.closest('.btn-remove-row')) {
                    const rows = wrapper.querySelectorAll('.row-obat');
                    if (rows.length > 1) {
                        e.target.closest('.row-obat').remove();
                    } else {
                        alert('Minimal satu baris obat pada resep.');
                    }
                }
            });
        });
    </script>
</x-app-layout>
