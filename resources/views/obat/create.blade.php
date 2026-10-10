<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Tambah Data Obat Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-hfc-dark">Input Master Obat</h3>
                    </div>
                    <a href="{{ route('obat.index') }}" class="text-xs font-semibold text-gray-500 hover:text-hfc-primary">
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

                <form method="POST" action="{{ route('obat.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="kode_obat" :value="__('Kode Obat')" />
                        <x-text-input id="kode_obat" class="block mt-1 w-full font-mono font-bold text-hfc-primary" type="text" name="kode_obat" :value="old('kode_obat', $autoKodeObat)" required />
                        <span class="text-xs text-gray-400">Kode obat</span>
                    </div>

                    <div>
                        <x-input-label for="nama_obat" :value="__('Nama Obat / Merk')" />
                        <x-text-input id="nama_obat" class="block mt-1 w-full" type="text" name="nama_obat" :value="old('nama_obat')" required placeholder="Contoh: Paracetamol 500mg" />
                    </div>

                    <div>
                        <x-input-label for="harga" :value="__('Harga Satuan (Rp)')" />
                        <x-text-input id="harga" class="block mt-1 w-full" type="number" min="0" name="harga" :value="old('harga', 10000)" required placeholder="Contoh: 15000" />
                    </div>

                    <div>
                        <x-input-label for="stok" :value="__('Jumlah Stok')" />
                        <x-text-input id="stok" class="block mt-1 w-full" type="number" min="0" name="stok" :value="old('stok', 100)" required placeholder="Contoh: 50" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('obat.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition inline-flex items-center justify-center shrink-0">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Data Obat') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
