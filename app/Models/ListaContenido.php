<?php //por si acaso fuera necesario este modelo

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaContenido extends Model
{
    protected $table = 'lista_contenido';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lista_id',
        'contenido_id',
        'orden',
    ];

    public function lista()
    {
        return $this->belongsTo(Lista::class);
    }

    public function contenido()
    {
        return $this->belongsTo(Contenido::class);
    }
}
