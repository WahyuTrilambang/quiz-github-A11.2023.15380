<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Obat;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    // Role Admin: Export Data Dokter
    public function exportDokter()
    {
        $fileName = 'data-dokter.csv';
        $dokters = User::where('role', 'dokter')->get();

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nama Dokter', 'Email', 'Alamat', 'No. HP', 'No. KTP'];

        $callback = function() use($dokters, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // Add BOM for UTF-8 Excel support
            fputcsv($file, $columns);

            foreach ($dokters as $d) {
                fputcsv($file, [$d->nama ?? $d->name, $d->email, $d->alamat, $d->no_hp, $d->no_ktp]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Role Admin: Export Data Pasien
    public function exportPasien()
    {
        $fileName = 'data-pasien.csv';
        $pasiens = User::where('role', 'pasien')->get();

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName"
        ];

        $columns = ['No. RM', 'Nama Pasien', 'Email', 'Alamat', 'No. HP', 'No. KTP'];

        $callback = function() use($pasiens, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($pasiens as $p) {
                fputcsv($file, [$p->no_rm, $p->nama ?? $p->name, $p->email, $p->alamat, $p->no_hp, $p->no_ktp]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Role Admin: Export Data Obat
    public function exportObat()
    {
        $fileName = 'data-obat.csv';
        $obats = Obat::all();

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName"
        ];

        $columns = ['Nama Obat', 'Kemasan', 'Harga (Rp)'];

        $callback = function() use($obats, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($obats as $o) {
                fputcsv($file, [$o->nama_obat, $o->kemasan, $o->harga]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Role Dokter: Export Jadwal Periksa
    public function exportJadwal()
    {
        $fileName = 'jadwal-periksa.csv';
        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())->get();

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName"
        ];

        $columns = ['Hari', 'Jam Mulai', 'Jam Selesai'];

        $callback = function() use($jadwals, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($jadwals as $j) {
                fputcsv($file, [$j->hari, $j->jam_mulai, $j->jam_selesai]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Role Dokter: Export Riwayat Pasien
    public function exportRiwayat()
    {
        $fileName = 'riwayat-pasien.csv';
        $dokterId = Auth::id();

        $riwayats = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })->get();

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName"
        ];

        $columns = ['No. RM', 'Nama Pasien', 'Tanggal Periksa', 'Catatan', 'Total Biaya (Rp)'];

        $callback = function() use($riwayats, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($riwayats as $r) {
                fputcsv($file, [
                    $r->daftarPoli->pasien->no_rm ?? '-',
                    $r->daftarPoli->pasien->nama ?? $r->daftarPoli->pasien->name ?? '-',
                    $r->tgl_periksa,
                    $r->catatan,
                    $r->biaya_periksa
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}