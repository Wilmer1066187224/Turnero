<?php

namespace App\Http\Controllers;

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

        // 🎯 generar número (R001, A002, etc)
        $numero = $letra . str_pad($cantidad, 3, '0', STR_PAD_LEFT);

        $turno = Turno::create([
            'numero' => $numero,
            'servicio' => $servicio,
            'estado' => 'esperando'
        ]);

        // 🔥 RESPUESTA LIMPIA
        return response()->json([
            'id' => $turno->id,
            'numero' => (string) $turno->numero,
            'servicio' => $turno->servicio,
            'estado' => $turno->estado
        ]);
    }

    public function llamar($modulo)
    {
        $turno = Turno::where('estado', 'esperando')->first();

        if (!$turno) {
            return response()->json([
                'mensaje' => 'No hay turnos'
            ]);
        }

        $turno->update([
            'estado' => 'llamado',
            'modulo' => $modulo
        ]);

        // 🔥 CLAVE: FORZAR ENVÍO CORRECTO
        return response()->json([
            'id' => $turno->id,
            'numero' => (string) $turno->numero, // 👈 AQUÍ ESTÁ LA MAGIA
            'modulo' => $turno->modulo,
            'servicio' => $turno->servicio,
            'estado' => $turno->estado
        ]);
    }

    public function lista()
    {
        return Turno::where('estado', 'llamado')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'numero' => (string) $t->numero,
                    'modulo' => $t->modulo
                ];
            });
    }
}