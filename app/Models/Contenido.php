<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenido extends Model{
    protected $table='contenido';
    protected $primaryKey = 'id';

    protected $fillable = [
        'titulo',
        'tipo',
        'fecha_lanzamiento',
        'sinopsis',
        'categoria',
        'detalles'
    ];

    protected $casts = [
        'detalles' => 'array',
        'fecha_lanzamiento' => 'date',
    ];

    public function listas(){
        return $this->belongsToMany(Lista::class, 'lista_contenido')
            ->withTimestamps()
            ->withPivot('orden');
    }

    public function actividad()
    {
        return $this->hasMany(Actividad::class);
    }
}