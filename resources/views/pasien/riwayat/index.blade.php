<x-layouts.app title="Riwayat Pendaftaran Poli">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Riwayat Pendaftaran Poli</h2>
        <p class="text-sm text-slate-500">Daftar histori pendaftaran dan hasil pemeriksaan kesehatan Anda.</p>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-6">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center">No</th>
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Jadwal Periksa</th>
                            <th class="px-6 py-4 text-center">No. Antrian</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($riwayats as $index => $item)
                            @php $periksa = $item->periksas->first(); @endphp
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">
                                        Poli {{ $item->jadwalPeriksa->dokter->poli->nama_poli ?? 'Umum' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Dr. {{ $item->jadwalPeriksa->dokter->nama ?? $item->jadwalPeriksa->dokter->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->jadwalPeriksa->hari }}, 
                                    {{ \Carbon\Carbon::parse($item->jadwalPeriksa->jam_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($item->jadwalPeriksa->jam_selesai)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 font-extrabold text-xs rounded-lg">
                                        {{ $item->no_antrian }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($periksa)
                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                            Selesai Diperiksa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                            Menunggu Antrian
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($periksa)
                                        <a href="{{ route('pasien.riwayat.show', $item->id) }}" 
                                           class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                            <i class="fas fa-file-medical mr-1"></i> Detail
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">
                                    Belum ada riwayat pendaftaran poli.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>