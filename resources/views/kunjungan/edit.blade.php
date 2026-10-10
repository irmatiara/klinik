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
                            'antri_triage' => 'Pemeriksaan Awal (Perawat)',
                            'antri_dokter' => 'Menunggu Dokter',
                            'periksa' => 'Pemeriksaan Dokter',
                            'kasir' => 'Kasir / Pembayaran',
                            'apotek' => 'Apotek / Obat',
                            'selesai' => 'Selesai',
                            'batal' => 'Dibatalkan',
                        ];
                    @endphp

                    <div>
                        <x-input-label :value="__('Status Alur Pelayanan (Otomatis Sistem)')" />
                        <div class="mt-1.5 p-3.5 bg-gray-50 border border-gray-200/80 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 font-medium">Status Berjalan:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusBadge[$kunjungan->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabel[$kunjungan->status] ?? strtoupper($kunjungan->status) }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400 italic">Generate otomatis sesuai alur</span>
                        </div>

                        <!-- Opsi Mengubah Ke Dibatalkan -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Tindakan Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm text-sm font-medium text-gray-800" required>
                                <option value="{{ $kunjungan->status }}" {{ old('status', $kunjungan->status) != 'batal' ? 'selected' : '' }}>
                                    Pertahankan Status ({{ $statusLabel[$kunjungan->status] ?? $kunjungan->status }})
                                </option>
                                @if($kunjungan->status !== 'batal')
                                    <option value="batal" {{ old('status', $kunjungan->status) == 'batal' ? 'selected' : '' }}>
                                        ✕ Batalkan Antrian (DIBATALKAN)
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('kunjungan.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition inline-flex items-center justify-center shrink-0">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Update Data Kunjungan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
