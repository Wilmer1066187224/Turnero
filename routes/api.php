<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TurnoController;

/*
|--------------------------------------------------------------------------
| RUTAS DEL TURNERO
|--------------------------------------------------------------------------
*/

Route::post('/turno/{servicio}', [TurnoController::class, 'generar']);

Route::post('/llamar/{modulo}', [TurnoController::class, 'llamar']);

Route::get('/turnos', [TurnoController::class, 'lista']);