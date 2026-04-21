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
 Schema::create('lista_contenido', function (Blueprint $table) {
    $table->id();

    $table->foreignId('lista_id')
          ->constrained('lista')
          ->onDelete('cascade')
          ->onUpdate('cascade');

    $table->foreignId('contenido_id')
          ->constrained('contenido')
          ->onDelete('cascade')
          ->onUpdate('cascade');

    $table->integer('orden')->nullable();

    $table->timestamps();
});




}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_contenido');
    }
};