<?php

namespace App\Http\Controllers;

use App\Events\TurnoLlamado;
use App\Models\Turno;

class TurnoController extends Controller
{

   public function generar($servicio)
{
    // 🔤 prefijos
    $prefijos = [
        'renta' => 'R',
        'adulto' => 'A',
        'iva' => 'I'
    ];

    $letra = $prefijos[$servicio] ?? 'X';

    // 🔢 contar turnos por servicio
    $cantidad = Turno::where('servicio', $servicio)
        ->whereDate('created_at', today())
        ->count() + 1;

    // 🎯 generar número
    $numero = $letra . str_pad($cantidad, 3, '0', STR_PAD_LEFT);

    $turno = Turno::create([
        'numero' => $numero,
        'servicio' => $servicio,
        'estado' => 'esperando'
    ]);

    return response()->json($turno);
}

    public function llamar($modulo)
    {

        $turno = Turno::where('estado','esperando')->first();

        if(!$turno){

            return response()->json([
                'mensaje'=>'No hay turnos'
            ]);

        }

        $turno->update([

            'estado'=>'llamado',

            'modulo'=>$modulo

        ]);

        return response()->json($turno);

    }


    public function lista()
    {
        return Turno::where('estado','llamado')
        ->latest()
        ->take(10)
        ->get();
    }

}