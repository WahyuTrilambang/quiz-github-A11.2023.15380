<x-layouts.app title="Dashboard Pasien">

    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Dashboard Pasien
            </h2>
            <p class="text-sm text-slate-500">Selamat datang di Panel Layanan Poliklinik.</p>
        </div>
        
        {{-- Badge Indikator Live Connection --}}
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full text-xs font-semibold self-start md:self-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Real-Time Queue Active
        </div>
    </div>

    {{-- ================= BANNER ANTRIAN AKTIF ================= --}}
    @if($antrianAktif)
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="inline-block px-3 py-1 bg-white/20 text-xs font-bold uppercase tracking-wider rounded-md mb-2">
                        Antrian Aktif Anda
                    </span>
                    <h3 class="text-2xl font-bold">
                        Poli {{ $antrianAktif->jadwalPeriksa->poli->nama_poli ?? $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poli Umum' }}
                    </h3>
                    <p class="text-indigo-100 text-sm mt-1">
                        <i class="fas fa-user-md mr-1"></i> Dr. {{ $antrianAktif->jadwalPeriksa->dokter->nama ?? $antrianAktif->jadwalPeriksa->dokter->name }}
                    </p>
                    <p class="text-indigo-200 text-xs mt-1">
                        <i class="fas fa-clock mr-1"></i> {{ $antrianAktif->jadwalPeriksa->hari }}, {{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_selesai)->format('H:i') }}
                    </p>
                </div>

                <div class="flex items-center gap-4 bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10">
                    <div class="text-center px-4 border-r border-white/20">
                        <span class="text-xs uppercase text-indigo-200 block font-semibold">Nomor Anda</span>
                        <span class="text-3xl font-extrabold text-amber-300">{{ $antrianAktif->no_antrian }}</span>
                    </div>
                    <div class="text-center px-4">
                        <span class="text-xs uppercase text-indigo-200 block font-semibold">Sedang Dilayani</span>
                        <span id="banner-sedang-dilayani" class="text-3xl font-extrabold text-emerald-300 transition-all duration-300">
                            {{ $nomorSedangDilayaniPasien ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-8 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Anda Belum Memiliki Antrian Aktif</h3>
                <p class="text-slate-500 text-sm">Silakan lakukan pendaftaran poli untuk mendapatkan nomor antrian pemeriksaan.</p>
            </div>
            <a href="{{ route('pasien.daftar') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl transition shadow-md shadow-indigo-200">
                <i class="fas fa-plus-circle mr-1"></i> Daftar Poli
            </a>
        </div>
    @endif

    {{-- ================= TABEL DAFTAR JADWAL POLIKLINIK ================= --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Daftar Jadwal Poliklinik & Dokter</h3>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center">No</th>
                            <th class="px-6 py-4">Poli</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Periksa</th>
                            <th class="px-6 py-4 text-center">Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($jadwalList as $index => $jadwal)
                            @php
                                $idPoli = $jadwal->poli->id ?? $jadwal->dokter->id_poli ?? null;
                            @endphp
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $jadwal->poli->nama_poli ?? $jadwal->dokter->poli->nama_poli ?? 'Poli Umum' }}
                                </td>
                                <td class="px-6 py-4">
                                    Dr. {{ $jadwal->dokter->nama ?? $jadwal->dokter->name }}
                                </td>
                                <td class="px-6 py-4">{{ $jadwal->hari }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-emerald-100 text-emerald-700 font-bold text-xs rounded-full">
                                        No. <span id="antrian-poli-{{ $idPoli }}" class="antrian-poli-badge ml-1">{{ $jadwal->nomor_sedang_dilayani ?? '-' }}</span>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">
                                    Belum ada jadwal poliklinik yang aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script Real-Time Listener WebSocket Laravel Echo --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Echo !== 'undefined') {
                Echo.channel('antrian-channel')
                    .listen('.antrian.updated', (e) => {
                        console.log('Event Broadcast Diterima:', e);

                        // 1. Update Nomor Antrian di Tabel Poli Spesifik
                        if (e.idPoli) {
                            const tableBadge = document.getElementById('antrian-poli-' + e.idPoli);
                            if (tableBadge) {
                                tableBadge.innerText = e.noAntrianDipanggil;
                                tableBadge.classList.add('scale-125', 'text-emerald-900');
                                setTimeout(() => tableBadge.classList.remove('scale-125', 'text-emerald-900'), 500);
                            }
                        }

                        // 2. Update Banner Antrian Aktif Pasien (Jika Poli Sesuai)
                        const bannerSedangDilayani = document.getElementById('banner-sedang-dilayani');
                        if (bannerSedangDilayani) {
                            bannerSedangDilayani.innerText = e.noAntrianDipanggil;
                        }
                    });
            }
        });
    </script>

</x-layouts.app>