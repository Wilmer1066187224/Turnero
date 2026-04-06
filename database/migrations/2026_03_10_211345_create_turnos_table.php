<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('turnos', function (Blueprint $table) {
    $table->id();
    $table->string("numero");
    $table->string("servicio");
    $table->integer("modulo")->nullable(); // ✅ CORRECTO
    $table->enum('estado',['esperando','llamado','atendido']);
    $table->timestamps();

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
