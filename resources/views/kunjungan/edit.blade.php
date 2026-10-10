<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Edit / Update Status Kunjungan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-hfc-dark">Update Antrian Pasien</h3>
                        <p class="text-xs text-gray-500">Pasien: {{ $kunjungan->pasien->nama ?? '-' }} ({{ $kunjungan->pasien->no_rm ?? '-' }})</p>
                    </div>
                    <a href="{{ route('kunjungan.index') }}" class="text-xs font-semibold text-gray-500 hover:text-hfc-primary">
                        &larr; Kembali
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded-xl">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('kunjungan.update', $kunjungan->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="no_antrian" :value="__('Nomor Antrian')" />
                        <x-text-input id="no_antrian" class="block mt-1 w-full font-mono font-bold text-hfc-primary" type="text" name="no_antrian" :value="old('no_antrian', $kunjungan->no_antrian)" required />
                    </div>

                    <div>
                        <x-input-label for="tanggal_kunjungan" :value="__('Tanggal & Waktu Kunjungan')" />
                        <x-text-input id="tanggal_kunjungan" class="block mt-1 w-full" type="datetime-local" name="tanggal_kunjungan" :value="old('tanggal_kunjungan', \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('Y-m-d\TH:i'))" required />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status Alur Pelayanan')" />
                        <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm text-sm" required>
                            <option value="antri_triage" {{ old('status', $kunjungan->status) == 'antri_triage' ? 'selected' : '' }}>1. ANTRI TRIAGE (Menunggu Cek Vital Perawat)</option>
                            <option value="antri_dokter" {{ old('status', $kunjungan->status) == 'antri_dokter' ? 'selected' : '' }}>2. ANTRI DOKTER (Menunggu Dipanggil Dokter)</option>
                            <option value="periksa" {{ old('status', $kunjungan->status) == 'periksa' ? 'selected' : '' }}>3. PERIKSA (Sedang Diperiksa Dokter)</option>
                            <option value="kasir" {{ old('status', $kunjungan->status) == 'kasir' ? 'selected' : '' }}>4. KASIR (Menunggu Pembayaran)</option>
                            <option value="apotek" {{ old('status', $kunjungan->status) == 'apotek' ? 'selected' : '' }}>5. APOTEK (Menunggu Penyerahan Obat)</option>
                            <option value="selesai" {{ old('status', $kunjungan->status) == 'selesai' ? 'selected' : '' }}>6. SELESAI (Pelayanan Selesai)</option>
                            <option value="batal" {{ old('status', $kunjungan->status) == 'batal' ? 'selected' : '' }}>DIBATALKAN</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('kunjungan.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            Batal
                        </a>
                        <x-primary-button class="bg-hfc-primary hover:bg-hfc-hover rounded-xl px-6 py-2.5">
                            {{ __('Update Data Kunjungan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
