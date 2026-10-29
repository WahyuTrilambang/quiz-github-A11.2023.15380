<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class PasienDashboardController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();
        $antrianAktif = DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', $pasienId)
            ->whereDoesntHave('periksas')
            ->latest()
            ->first();

        $nomorSedangDilayaniPasien = null;
        if ($antrianAktif) {
            $nomorSedangDilayaniPasien = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->whereHas('periksas')
                ->max('no_antrian') ?? 0;
        }

        $jadwalList = JadwalPeriksa::with(['dokter.poli'])
            ->get()
            ->map(function ($jadwal) {
                $sedangDilayani = DaftarPoli::where('id_jadwal', $jadwal->id)
                    ->whereHas('periksas')
                    ->max('no_antrian') ?? 0;

                $jadwal->nomor_sedang_dilayani = $sedangDilayani;
                return $jadwal;
            });

        return view('pasien.dashboard', compact('antrianAktif', 'nomorSedangDilayaniPasien', 'jadwalList'));
    }
}