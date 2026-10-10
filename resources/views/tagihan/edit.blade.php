<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hfc-dark leading-tight">
            {{ __('Proses Transaksi Kasir & Pembayaran Pasien') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Flash Message -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">

                <!-- Tombol Kembali -->
                <div class="mb-4">
                    <a href="{{ route('tagihan.index') }}" class="text-xs font-semibold text-gray-500 hover:text-hfc-primary transition inline-flex items-center gap-1">
                        &larr; kembali
                    </a>
                </div>

                <!-- Header Info Pasien -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-4 mb-6">
                    <div>
                        <span class="text-xs font-bold text-hfc-primary uppercase tracking-wider block">Nota Tagihan Pelayanan Klinik</span>
                        <h3 class="text-2xl font-bold text-hfc-dark mt-0.5">{{ $tagihan->kunjungan->pasien->nama ?? '-' }}</h3>
                        <p class="text-xs text-gray-500">No. RM: <span class="font-mono font-bold">{{ $tagihan->kunjungan->pasien->no_rm ?? '-' }}</span> | Waktu Daftar: {{ \Carbon\Carbon::parse($tagihan->kunjungan->tanggal_kunjungan)->format('d M Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">No. Antrian</span>
                        <div class="text-3xl font-black text-hfc-primary font-mono">
                            {{ $tagihan->kunjungan->no_antrian }}
                        </div>
                    </div>
                </div>

                <!-- Rincian Biaya (Invoice Breakdown) -->
                <div class="mb-6">
                    <h4 class="text-md font-bold text-hfc-dark mb-3">Rincian Biaya & Resep Obat</h4>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-gray-100/70 text-gray-600 font-bold border-b border-gray-200">
                                    <th class="p-3">Deskripsi Layanan / Obat</th>
                                    <th class="p-3 text-center">Jumlah / Qty</th>
                                    <th class="p-3 text-right">Harga Satuan</th>
                                    <th class="p-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="p-3 font-semibold text-gray-800">Biaya Administrasi & Layanan Dokter</td>
                                    <td class="p-3 text-center font-mono">1</td>
                                    <td class="p-3 text-right font-medium text-gray-700">Rp {{ number_format($tagihan->biaya_layanan, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right font-bold text-gray-800">Rp {{ number_format($tagihan->biaya_layanan, 0, ',', '.') }}</td>
                                </tr>
                                @forelse($tagihan->kunjungan->resepObats as $resep)
                                @php
                                $subtotal = ($resep->obat->harga ?? 0) * $resep->jumlah;
                                @endphp
                                <tr>
                                    <td class="p-3 text-gray-700">
                                        <span class="font-semibold text-gray-900">{{ $resep->obat->nama_obat ?? 'Obat' }}</span>
                                        <span class="text-xs text-gray-400 block font-mono">({{ $resep->aturan_pakai }})</span>
                                    </td>
                                    <td class="p-3 text-center font-mono">{{ $resep->jumlah }}</td>
                                    <td class="p-3 text-right font-medium text-gray-700">Rp {{ number_format($resep->obat->harga ?? 0, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-3 text-xs text-gray-400 italic text-center">Tidak ada resep obat tambahan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-purple-50/80 font-bold text-base text-hfc-dark border-t-2 border-purple-200">
                                    <td colspan="3" class="p-4 text-right">TOTAL TAGIHAN KASIR:</td>
                                    <td class="p-4 text-right font-black text-hfc-primary text-xl font-mono">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Form Konfirmasi Transaksi Pembayaran -->
                @if($tagihan->status_bayar == 'belum_lunas')
                <form method="POST" action="{{ route('tagihan.update', $tagihan->id) }}" class="bg-gray-50/80 p-5 rounded-2xl border border-gray-200">
                    @csrf
                    @method('PUT')

                    <h4 class="text-md font-bold text-hfc-dark border-b border-gray-200 pb-2">Form Transaksi Pembayaran</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-5">
                        <div>
                            <x-input-label for="metode_pembayaran" :value="__('Metode Pembayaran')" />
                            <select id="metode_pembayaran" name="metode_pembayaran" class="block mt-1 w-full border-gray-300 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl text-sm font-semibold" required>
                                <option value="Tunai">Tunai / Cash</option>
                                <option value="QRIS">QRIS / Digital Payment</option>
                                <option value="Transfer Bank">Transfer Bank / E-Wallet</option>
                                <option value="Debit / Kartu Kredit">Debit / Kartu Kredit</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="bayar" :value="__('Uang Diterima / Bayar (Rp)')" />
                            <x-text-input id="bayar" class="block mt-1 w-full font-bold text-lg text-emerald-700" type="number" min="{{ $tagihan->total_tagihan }}" name="bayar" :value="old('bayar', $tagihan->total_tagihan)" required />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-200/80">
                        <a href="{{ route('tagihan.index') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-xl transition inline-flex items-center justify-center shrink-0">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Konfirmasi Lunas & Oper ke Apotek') }}
                        </x-primary-button>
                    </div>
                </form>
                @else
                <!-- Status LUNAS -->
                <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-200">
                    <div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white">
                            LUNAS
                        </span>
                        <h4 class="text-lg font-bold text-emerald-900 mt-1">Pembayaran Telah Selesai</h4>
                        <p class="text-xs text-emerald-700">Pasien telah melunasi seluruh biaya dan data telah dikirim ke bagian Apotek / Farmasi.</p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>