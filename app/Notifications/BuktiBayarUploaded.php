<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BuktiBayarUploaded extends Notification
{
    use Queueable;

    protected $namaPasien;
    protected $idPeriksa;

    public function __construct($namaPasien, $idPeriksa)
    {
        $this->namaPasien = $namaPasien;
        $this->idPeriksa = $idPeriksa;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        // Gunakan route periksa-pasien.index atau url '/dokter/periksa-pasien'
        $targetUrl = route('periksa-pasien.index'); 

        return [
            'judul'   => 'Bukti Pembayaran Baru',
            'pesan'   => "Pasien {$this->namaPasien} telah mengunggah bukti pembayaran.",
            'url'     => $targetUrl,
            'icon'    => 'fas fa-file-invoice-dollar text-indigo-500',
        ];
    }
}