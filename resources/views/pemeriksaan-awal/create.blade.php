<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Pemeriksaan Awal Pasien (Stasiun Perawat)') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                
                <!-- Header Card Info Pasien -->
                <div class="mb-6 bg-hfc-bg/60 p-5 rounded-2xl border border-purple-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Pasien Dipanggil</span>
                        <h3 class="text-xl font-bold text-hfc-dark mt-0.5">{{ $kunjungan->pasien->nama ?? '-' }}</h3>
                        <p class="text-xs text-gray-500">No. RM: <span class="font-mono font-bold">{{ $kunjungan->pasien->no_rm ?? '-' }}</span> | Jenis Kelamin: {{ ($kunjungan->pasien->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">No. Antrian</span>
                        <div class="text-3xl font-black text-hfc-primary font-mono">
                            {{ $kunjungan->no_antrian }}
                        </div>
                    </div>
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

                <form method="POST" action="{{ route('pemeriksaan-awal.store', $kunjungan->id) }}" class="space-y-6">
                    @csrf

                    <div class="border-b border-gray-100 pb-3">
                        <h4 class="text-md font-bold text-hfc-dark flex items-center gap-2">
                            Form Input Pemeriksaan Awal Pasien
                        </h4>
                        <p class="text-xs text-gray-500">Diisi oleh Perawat sebelum pasien masuk ke ruang pemeriksaan Dokter.</p>
                    </div>

                    <!-- 1. Suhu Tubuh -->
                    <div>
                        <x-input-label for="suhu" :value="__('1. Suhu Tubuh (°C)')" />
                        <div class="relative mt-1">
                            <x-text-input id="suhu" class="block w-full pr-12 font-medium" type="number" step="0.1" min="30" max="45" name="suhu" :value="old('suhu', $rekamMedis->suhu ?? 36.5)" required placeholder="Contoh: 36.5" />
                            <span class="absolute right-4 top-2.5 text-gray-400 text-sm font-semibold">°C</span>
                        </div>
                        <span class="text-xs text-gray-400">Normal: 36.5°C - 37.5°C</span>
                    </div>

                    <!-- 2. Tekanan Darah -->
                    <div>
                        <x-input-label for="tekanan_darah" :value="__('2. Tekanan Darah (mmHg)')" />
                        <x-text-input id="tekanan_darah" class="block mt-1 w-full font-medium" type="text" name="tekanan_darah" :value="old('tekanan_darah', $rekamMedis->tekanan_darah ?? '120/80')" required placeholder="Contoh: 120/80" />
                        <span class="text-xs text-gray-400">Format: Systolic/Diastolic (Contoh: 120/80)</span>
                    </div>

                    <!-- 3. Berat Badan -->
                    <div>
                        <x-input-label for="berat_badan" :value="__('3. Berat Badan (kg)')" />
                        <div class="relative mt-1">
                            <x-text-input id="berat_badan" class="block w-full pr-12 font-medium" type="number" step="0.1" min="1" max="300" name="berat_badan" :value="old('berat_badan', $rekamMedis->berat_badan ?? 60)" required placeholder="Contoh: 60" />
                            <span class="absolute right-4 top-2.5 text-gray-400 text-sm font-semibold">kg</span>
                        </div>
                        <span class="text-xs text-gray-400">Dalam satuan kilogram (kg)</span>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('pemeriksaan-awal.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition inline-flex items-center justify-center shrink-0">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan & Oper Pasien ke Dokter') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
