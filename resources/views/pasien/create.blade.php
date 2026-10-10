<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Pendaftaran Pasien Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-hfc-dark">Form Biodata Pasien Baru</h3>
                        <p class="text-xs text-gray-500">Lengkapi data diri pasien di bawah ini</p>
                    </div>
                    <a href="{{ route('pasien.index') }}" class="text-xs font-semibold text-gray-500 hover:text-hfc-primary">
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

                <form method="POST" action="{{ route('pasien.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="no_rm" :value="__('Nomor Rekam Medis (No. RM)')" />
                        <x-text-input id="no_rm" class="block mt-1 w-full font-mono font-bold text-hfc-primary bg-gray-50" type="text" name="no_rm" :value="old('no_rm', $autoNoRm)" required readonly />
                        <span class="text-xs text-gray-400 mt-1 block">No. RM digenerate otomatis oleh sistem</span>
                    </div>

                    <div>
                        <x-input-label for="nama" :value="__('Nama Lengkap Pasien')" />
                        <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" placeholder="Masukkan nama lengkap pasien" required autofocus />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tgl_lahir" :value="__('Tanggal Lahir')" />
                            <x-text-input id="tgl_lahir" class="block mt-1 w-full" type="date" name="tgl_lahir" :value="old('tgl_lahir')" required />
                        </div>

                        <div>
                            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                            <select id="jenis_kelamin" name="jenis_kelamin" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm text-sm" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="no_telp" :value="__('Nomor Telepon / WhatsApp')" />
                        <x-text-input id="no_telp" class="block mt-1 w-full" type="text" name="no_telp" :value="old('no_telp')" placeholder="08xxxxxxxxxx" required />
                    </div>

                    <div>
                        <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
                        <textarea id="alamat" name="alamat" rows="3" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm text-sm" placeholder="Masukkan alamat lengkap rumah pasien" required>{{ old('alamat') }}</textarea>
                    </div>

                    <!-- Hidden flag if registering directly from queue -->
                    <input type="hidden" name="redirect_to_antrian" value="1">

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('pasien.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            Batal
                        </a>
                        <x-primary-button class="bg-hfc-primary hover:bg-hfc-hover rounded-xl px-6 py-2.5">
                            {{ __('Simpan & Lanjut Buat Antrian') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
