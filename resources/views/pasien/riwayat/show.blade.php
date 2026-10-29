<x-layouts.app title="Detail Hasil Pemeriksaan">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Detail Hasil Pemeriksaan</h2>
            <p class="text-sm text-slate-500">Rincian diagnosa, resep obat, dan total biaya periksa.</p>
        </div>
        <a href="{{ route('pasien.riwayat.index') }}" 
           class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @php $periksa = $riwayat->periksas->first(); @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Card Info Pasien & Dokter --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="text-md font-bold text-slate-700 border-b pb-2">Informasi Pendaftaran</h3>
            
            <div class="grid grid-cols-2 gap-2 text-sm">
                <span class="text-slate-500">Poliklinik:</span>
                <span class="font-semibold text-slate-800">Poli {{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? 'Umum' }}</span>

                <span class="text-slate-500">Dokter Pemeriksa:</span>
                <span class="font-semibold text-slate-800">Dr. {{ $riwayat->jadwalPeriksa->dokter->nama ?? $riwayat->jadwalPeriksa->dokter->name }}</span>

                <span class="text-slate-500">Tanggal Periksa:</span>
                <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->translatedFormat('d F Y, H:i') }} WIB</span>

                <span class="text-slate-500">Keluhan Pasien:</span>
                <span class="font-semibold text-slate-800">{{ $riwayat->keluhan }}</span>
            </div>
        </div>

        {{-- Card Diagnosa & Obat --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="text-md font-bold text-slate-700 border-b pb-2">Hasil Diagnosa & Resep</h3>

            <div>
                <label class="text-xs text-slate-500 font-semibold uppercase">Catatan / Diagnosa Dokter</label>
                <p class="p-3 bg-slate-50 rounded-xl text-sm text-slate-800 mt-1 border border-slate-100">
                    {{ $periksa->catatan ?? '-' }}
                </p>
            </div>

            <div>
                <label class="text-xs text-slate-500 font-semibold uppercase">Daftar Obat Resep</label>
                <ul class="mt-1 space-y-1">
                    @forelse($periksa->detailPeriksas as $detail)
                        <li class="flex items-center justify-between p-2 bg-slate-50 rounded-lg text-xs">
                            <span class="font-semibold text-slate-700">{{ $detail->obat->nama_obat }} ({{ $detail->obat->kemasan }})</span>
                            <span class="text-emerald-600 font-bold">Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</span>
                        </li>
                    @empty
                        <li class="text-xs text-slate-400 italic">Tidak ada resep obat.</li>
                    @endforelse
                </ul>
            </div>

            <div class="pt-2 border-t flex justify-between items-center">
                <span class="text-sm font-bold text-slate-700">Total Biaya Pemeriksaan</span>
                <span class="text-xl font-extrabold text-indigo-600">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t">
    <label class="text-xs text-slate-500 font-semibold uppercase block mb-2">Status & Bukti Pembayaran</label>

    @if($periksa->status_pembayaran == 'lunas')
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center justify-between">
            <span class="text-sm font-bold"><i class="fas fa-check-circle mr-1"></i> Pembayaran Lunas</span>
            <a href="{{ asset('storage/' . $periksa->bukti_pembayaran) }}" target="_blank" class="text-xs text-emerald-800 underline font-semibold">Lihat Bukti</a>
        </div>
    @elseif($periksa->status_pembayaran == 'menunggu_konfirmasi')
        <div class="p-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl flex items-center justify-between">
            <span class="text-sm font-bold"><i class="fas fa-clock mr-1"></i> Menunggu Konfirmasi Admin</span>
            <a href="{{ asset('storage/' . $periksa->bukti_pembayaran) }}" target="_blank" class="text-xs text-amber-800 underline font-semibold">Lihat Bukti</a>
        </div>
    @else
        <form action="{{ route('pasien.riwayat.upload', $periksa->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="file" name="bukti_pembayaran" required class="file-input file-input-bordered file-input-sm w-full rounded-xl" />
            @error('bukti_pembayaran')
                <span class="text-rose-500 text-xs block">{{ $message }}</span>
            @enderror
            <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-upload mr-1"></i> Upload Bukti Transfer
            </button>
        </form>
    @endif
</div>

</x-layouts.app>