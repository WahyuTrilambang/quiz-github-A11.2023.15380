<?php

use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Pasien\PoliController;
use App\Http\Controllers\Pasien\PasienDashboardController;
use App\Http\Controllers\Pasien\RiwayatPendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Route Profile & Ganti Password (Berlaku Semua Role)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Export khusus Role Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/export/dokter', [ExportController::class, 'exportDokter'])->name('admin.export.dokter');
    Route::get('/export/pasien', [ExportController::class, 'exportPasien'])->name('admin.export.pasien');
    Route::get('/export/obat', [ExportController::class, 'exportObat'])->name('admin.export.obat');
});

// Export khusus Role Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/export/jadwal', [ExportController::class, 'exportJadwal'])->name('dokter.export.jadwal');
    Route::get('/export/riwayat', [ExportController::class, 'exportRiwayat'])->name('dokter.export.riwayat');
});

// Group Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::put('/pembayaran/{id}/konfirmasi', [PembayaranController::class, 'konfirmasi'])->name('admin.pembayaran.konfirmasi');
});

// Group Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])
        ->name('periksa-pasien.index');

    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])
        ->name('periksa-pasien.store');

    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])
        ->name('periksa-pasien.create');

    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
});

// Group Pasien
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('pasien.dashboard');
    Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PoliController::class, 'submit'])->name('pasien.daftar.submit');
    Route::get('/riwayat-pendaftaran', [RiwayatPendaftaranController::class, 'index'])->name('pasien.riwayat.index');
    Route::get('/riwayat-pendaftaran/{id}', [RiwayatPendaftaranController::class, 'show'])->name('pasien.riwayat.show');
    Route::post('/riwayat-pendaftaran/{id}/upload', [RiwayatPendaftaranController::class, 'uploadPembayaran'])->name('pasien.riwayat.upload');
});

Route::middleware('auth')->group(function () {
    Route::post('/notifications/mark-as-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.markAsRead');

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? route('dashboard'));
    })->name('notifications.readSingle');
});