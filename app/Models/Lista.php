<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = 'lista';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'privada',
    ];

    public function contenido()
    {
        return $this->belongsToMany(Contenido::class, 'lista_contenido')
                    ->withTimestamps()
                    ->withPivot('orden');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
