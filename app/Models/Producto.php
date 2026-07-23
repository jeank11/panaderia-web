<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'categoria_id',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'imagen',
        'estado'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
