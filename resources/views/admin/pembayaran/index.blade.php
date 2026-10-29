<x-layouts.app title="Kelola Pembayaran Pasien">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Kelola Pembayaran Pasien</h2>
        <p class="text-sm text-slate-500">Daftar transaksi dan verifikasi bukti transfer pasien.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 p-6">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center">No</th>
                        <th class="px-4 py-3">Nama Pasien / No. RM</th>
                        <th class="px-4 py-3">Total Biaya</th>
                        <th class="px-4 py-3 text-center">Bukti Bayar</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($pembayarans as $index => $item)
                        <tr class="border-t hover:bg-slate-50">
                            <td class="px-4 py-3 text-center font-bold">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-800">{{ $item->daftarPoli->pasien->nama ?? $item->daftarPoli->pasien->name }}</div>
                                <div class="text-xs text-slate-500">{{ $item->daftarPoli->pasien->no_rm }}</div>
                            </td>
                            <td class="px-4 py-3 font-bold text-indigo-600">Rp {{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border inline-flex items-center gap-1">
                                    <i class="fas fa-image"></i> Lihat Foto
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($item->status_pembayaran == 'lunas')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Lunas</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Menunggu Konfirmasi</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($item->status_pembayaran != 'lunas')
                                    <form action="{{ route('admin.pembayaran.konfirmasi', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm">
                                            Konfirmasi Lunas
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400">Belum ada transaksi pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>