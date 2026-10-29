<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\PembayaranDikonfirmasi;
use App\Models\Periksa;
use App\Models\User;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter'])
            ->whereNotNull('bukti_pembayaran')
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

   public function konfirmasi($id)
    {
        // Hapus relasi .user dari with()
        $periksa = Periksa::with('daftarPoli.pasien')->findOrFail($id);

        $periksa->update([
            'status_pembayaran' => 'lunas',
        ]);

        // Ambil ID / Email / No KTP pasien untuk mencocokkan dengan tabel users
        $pasien = $periksa->daftarPoli->pasien ?? null;

        if ($pasien) {
            // Cari user yang sesuai dengan pasien (berdasarkan id_pasien, email, atau role pasien)
            $pasienUser = User::where('role', 'pasien')
                ->where(function ($query) use ($pasien) {
                    if (isset($pasien->id_user)) {
                        $query->where('id', $pasien->id_user);
                    } elseif (isset($pasien->email)) {
                        $query->where('email', $pasien->email);
                    } else {
                        $query->where('nama', $pasien->nama ?? $pasien->name ?? '');
                    }
                })->first();

            if ($pasienUser) {
                $pasienUser->notify(new PembayaranDikonfirmasi());
            }
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS!');
    }
}