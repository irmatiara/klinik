<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Pendaftaran Antrian Pasien Baru / Lama') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-hfc-dark">Ambil Nomor Antrian</h3>
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

                <form method="POST" action="{{ route('kunjungan.store') }}" class="space-y-6">
                    @csrf

                    <!-- Card Nomor Antrian -->
                    <div class="bg-hfc-bg/60 p-5 rounded-2xl border border-purple-100 text-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Nomor Antrian Diterbitkan</span>
                        <div class="text-4xl font-black text-hfc-primary font-mono mt-1">
                            {{ $autoNoAntrian }}
                        </div>
                        <input type="hidden" name="no_antrian" value="{{ $autoNoAntrian }}">
                        <p class="text-[11px] text-gray-500 mt-1">Nomor antrian digenerate otomatis secara berurutan untuk hari ini</p>
                    </div>

                    <!-- Pilih Pasien Terdaftar -->
                    <div>
                        <x-input-label for="pasien_id" :value="__('Pilih Pasien Terdaftar')" />
                        <select id="pasien_id" name="pasien_id" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm text-sm" required>
                            <option value="">-- Pilih Pasien (Ketik / Cari Nama atau No. RM) --</option>
                            @foreach($pasiens as $p)
                                <option value="{{ $p->id }}" {{ (old('pasien_id', $selectedPasienId) == $p->id) ? 'selected' : '' }}>
                                    [{{ $p->no_rm }}] {{ $p->nama }} — {{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} ({{ $p->no_telp }})
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-2 text-xs text-gray-500 flex justify-between items-center">
                            <span>Pasien belum pernah berobat sebelumnya?</span>
                            <a href="{{ route('pasien.create') }}" class="text-hfc-primary font-bold hover:underline">
                                + Daftar Pasien Baru di Sini
                            </a>
                        </div>
                    </div>

                    <!-- Biaya Layanan / Admin -->
                    <div>
                        <x-input-label for="biaya_layanan" :value="__('Biaya Administrasi & Layanan (Rp)')" />
                        <x-text-input id="biaya_layanan" class="block mt-1 w-full" type="number" name="biaya_layanan" :value="old('biaya_layanan', 50000)" required />
                        <span class="text-xs text-gray-400">Biaya dasar registrasi & jasa pelayanan dokter klinik</span>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('kunjungan.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition inline-flex items-center justify-center shrink-0">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Antrian Pasien') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
