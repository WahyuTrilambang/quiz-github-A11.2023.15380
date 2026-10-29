<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Events\AntrianUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    /**
     * Dipanggil saat Dokter menekan tombol "Periksa"
     */
    public function create($id)
    {
        $daftarPoli = DaftarPoli::with('jadwalPeriksa.dokter.poli', 'pasien')->findOrFail($id);
        $obats = Obat::all();

        // Broadcast nomor antrian yang SEDANG DIPANGGIL/DILAYANI ke pasien via Reverb
        $idPoli = $daftarPoli->jadwalPeriksa->dokter->id_poli ?? null;
        $namaPoli = $daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poli';

        broadcast(new AntrianUpdated($idPoli, $daftarPoli->no_antrian, $namaPoli));

        return view('dokter.periksa-pasien.create', compact('daftarPoli', 'obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'tgl_periksa'    => 'required',
            'catatan'        => 'required|string',
            'biaya_periksa'  => 'required|integer',
            'obat'           => 'nullable|array',
        ]);

        // 1. Simpan Data Periksa
        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa'    => $request->tgl_periksa ?? now(),
            'catatan'        => $request->catatan,
            'biaya_periksa'  => $request->biaya_periksa,
        ]);

        // 2. Update status_periksa di tabel daftar_poli
        $daftarPoli = DaftarPoli::findOrFail($request->id_daftar_poli);
        $daftarPoli->update([
            'status_periksa' => 'sudah_diperiksa',
        ]);

        // 3. Simpan Rincian Obat jika ada
        if ($request->has('obat') && is_array($request->obat)) {
            foreach ($request->obat as $idObat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat'    => $idObat,
                ]);
            }
        }

        return redirect()
            ->route('periksa-pasien.index')
            ->with('message', 'Data periksa berhasil disimpan.')
            ->with('type', 'success');
    }
}