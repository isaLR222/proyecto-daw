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
    Schema::create('contenido', function (Blueprint $table) {
    $table->id();
    $table->string('titulo');
    $table->enum('tipo', ['libro', 'pelicula']);
    $table->date('fecha_lanzamiento')->nullable();
    $table->text('sinopsis')->nullable();
    $table->string('categoria')->nullable();
    $table->json('detalles')->nullable(); //es un json porque me es más comodo para no crear atributos vacios como isbn o duracion para asi tener pelis y libro en misma tabla
    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenido');
    }
};