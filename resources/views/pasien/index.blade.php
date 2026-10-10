<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Data Pasien') }}
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
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-hfc-dark">Daftar Pasien Terdaftar</h3>
                        <p class="text-sm text-gray-500">Kelola informasi data pasien klinik</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                        <form method="GET" action="{{ route('pasien.index') }}" class="flex items-center gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / No. RM / Telp..." class="text-sm border-gray-300 rounded-xl focus:ring-hfc-primary focus:border-hfc-primary px-3 py-2">
                            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('pasien.index') }}" class="px-3 py-2 bg-gray-50 text-gray-500 text-xs font-semibold rounded-xl">Reset</a>
                            @endif
                        </form>
                        <a href="{{ route('pasien.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-hfc-primary hover:bg-hfc-hover text-white text-sm font-semibold rounded-xl transition shadow-md shadow-hfc-primary/20 whitespace-nowrap">
                            + Tambah Pasien Baru
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-hfc-bg text-hfc-dark text-xs uppercase tracking-wider font-semibold border-b border-gray-100">
                                <th class="p-4">No. RM</th>
                                <th class="p-4">Nama Pasien</th>
                                <th class="p-4">Tgl Lahir</th>
                                <th class="p-4">JK</th>
                                <th class="p-4">No. Telepon</th>
                                <th class="p-4">Alamat</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($pasiens as $pasien)
                                <tr class="hover:bg-hfc-light/30 transition">
                                    <td class="p-4 font-bold text-hfc-primary font-mono">{{ $pasien->no_rm }}</td>
                                    <td class="p-4 font-medium text-hfc-dark">{{ $pasien->nama }}</td>
                                    <td class="p-4 text-gray-600">{{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d M Y') }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $pasien->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                            {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-gray-600">{{ $pasien->no_telp }}</td>
                                    <td class="p-4 text-gray-600 truncate max-w-xs">{{ $pasien->alamat }}</td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('kunjungan.create', ['pasien_id' => $pasien->id]) }}" class="px-3 py-1.5 bg-hfc-primary hover:bg-hfc-hover text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                            Buat Antrian
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400">Belum ada data pasien terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($pasiens, 'links'))
                    <div class="mt-6">
                        {{ $pasiens->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
