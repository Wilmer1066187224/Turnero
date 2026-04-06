<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TurnoLlamado implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $numero;
    public $modulo;

    public function __construct($turno)
    {
        $this->numero = $turno->numero;
        $this->modulo = $turno->modulo;
    }

    public function broadcastOn()
    {
        return new Channel('turnos'); // 🔥 DEBE COINCIDIR CON VUE
    }

    public function broadcastAs()
    {
        return 'nuevo-turno'; // 🔥 DEBE COINCIDIR CON VUE
    }
}