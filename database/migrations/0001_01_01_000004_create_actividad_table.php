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
  Schema::create('actividad', function (Blueprint $table) {
    $table->id();
    
    $table->foreignId('user_id')
          ->constrained('users')
          ->onDelete('cascade')
          ->onUpdate('cascade');

    $table->foreignId('contenido_id')
          ->constrained('contenido')
          ->onDelete('cascade')
          ->onUpdate('cascade');

    $table->enum('estado', [
        'visto', 'viendo', 'no_visto',
    ])->nullable();

    $table->tinyInteger('valoracion')->nullable(); 
    $table->text('comentario')->nullable();
    
     $table->boolean('favorito')->default(false);

    $table->timestamps();
});


}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad');
    }
};