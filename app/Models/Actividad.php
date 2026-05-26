<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividad';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'contenido_id',
        'estado',
        'valoracion',
        'comentario',
        'favorito',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contenido()
    {
        return $this->belongsTo(Contenido::class);
    }
}
