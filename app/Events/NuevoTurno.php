<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NuevoTurno implements ShouldBroadcast
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
        return new Channel('turnos'); // 🔥 IMPORTANTE
    }

    public function broadcastAs()
    {
        return 'nuevo-turno'; // 🔥 IMPORTANTE
    }
}