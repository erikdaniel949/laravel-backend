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
        Schema::create('telegramas', function (Blueprint $table) {
            $table->id('id_mesa');
            $table->string('provincia');
            $table->string('lista');
            $table->string('votos_diputados');
            $table->string('votos_senadores');
            $table->string('votos_blancos');
            $table->string('votos_nulos');
            $table->string('votos_recurridos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegramas');
    }
};
