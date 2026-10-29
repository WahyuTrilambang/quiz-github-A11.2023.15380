<?php

namespace App\Http\Controllers\Pasien;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Notifications\BuktiBayarUploaded;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        $pasienId = Auth::id();

        // Ambil riwayat pendaftaran pasien beserta relasi pemeriksaan & obat
        $riwayats = DaftarPoli::with([
                'jadwalPeriksa.dokter.poli',
                'periksas.detailPeriksas.obat'
            ])
            ->where('id_pasien', $pasienId)
            ->latest()
            ->get();

        return view('pasien.riwayat.index', compact('riwayats'));
    }

    public function show($id)
    {
        $pasienId = Auth::id();

        $riwayat = DaftarPoli::with([
                'jadwalPeriksa.dokter.poli',
                'periksas.detailPeriksas.obat'
            ])
            ->where('id_pasien', $pasienId)
            ->findOrFail($id);

        return view('pasien.riwayat.show', compact('riwayat'));
    }

    /**
     * Method untuk mengunggah bukti pembayaran pasien
     */
 public function uploadPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Panggil relasi dokter dan pasien standar (tanpa .user)
        $periksa = Periksa::with('daftarPoli.jadwalPeriksa.dokter', 'daftarPoli.pasien')->findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($periksa->bukti_pembayaran) {
                Storage::disk('public')->delete($periksa->bukti_pembayaran);
            }

            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

            $periksa->update([
                'bukti_pembayaran'  => $path,
                'status_pembayaran' => 'menunggu_konfirmasi',
            ]);

            // Kirim Notifikasi ke User yang memiliki role Dokter
            $namaPasien = $periksa->daftarPoli->pasien->nama ?? $periksa->daftarPoli->pasien->name ?? 'Pasien';
            $dokterUsers = User::where('role', 'dokter')->get();

            if ($dokterUsers->count() > 0) {
                Notification::send($dokterUsers, new BuktiBayarUploaded($namaPasien, $periksa->id));
            }
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah!');
    }
}