<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDikonfirmasi extends Notification
{
    use Queueable;

    protected $nomorRm;

    public function __construct($nomorRm = null)
    {
        $this->nomorRm = $nomorRm;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

   public function toArray($notifiable): array
    {
        return [
            'judul' => 'Pembayaran Diverifikasi',
            'pesan' => 'Pembayaran Anda telah diverifikasi LUNAS oleh admin. Terima kasih!',
            'url'   => url('/pasien/riwayat-pendaftaran'), // Menggunakan URL langsung agar aman dari route error
            'icon'  => 'fas fa-check-circle text-emerald-500',
        ];
    }
}