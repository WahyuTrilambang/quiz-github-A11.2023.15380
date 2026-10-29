<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('message'))
        <div class="mb-6 p-4 rounded-xl flex items-center gap-3 border shadow-sm {{ session('type') == 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
            <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
            <span class="font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4 text-center">No Antrian</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-sm text-slate-700">
                        @forelse($daftarPasien as $daftar)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                            {{-- No --}}
                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Nama Pasien --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $daftar->pasien->nama ?? ($daftar->pasien->name ?? '-') }}
                            </td>

                            {{-- Keluhan --}}
                            <td class="px-6 py-4 text-slate-600">
                                {{ $daftar->keluhan ?? '-' }}
                            </td>

                            {{-- Nomor Antrian --}}
                            <td class="px-6 py-4 text-center font-bold text-indigo-600">
                                {{ $daftar->no_antrian }}
                            </td>

                            {{-- Status Periksa --}}
                            <td class="px-6 py-4 text-center">
                                @if($daftar->status_periksa == 'sudah_diperiksa')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Sudah Diperiksa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Diperiksa
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                @if($daftar->status_periksa == 'sudah_diperiksa')
                                    <a href="{{ route('periksa-pasien.create', $daftar->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                                        <i class="fas fa-edit text-xs"></i>
                                        Edit Periksa
                                    </a>
                                @else
                                    <a href="{{ route('periksa-pasien.create', $daftar->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                        <i class="fas fa-stethoscope text-xs"></i>
                                        Periksa
                                    </a>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada antrian pasien untuk diperiksa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>