<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idPoli;
    public $noAntrianDipanggil;
    public $namaPoli;

    public function __construct($idPoli, $noAntrianDipanggil, $namaPoli)
    {
        $this->idPoli = $idPoli;
        $this->noAntrianDipanggil = $noAntrianDipanggil;
        $this->namaPoli = $namaPoli;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'antrian.updated';
    }
}