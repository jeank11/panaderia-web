<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [

        'codigo',

        'cliente_id',

        'fecha_pedido',

        'fecha_entrega',

        'hora_entrega',

        'tipo_entrega',

        'direccion_entrega',

        'observaciones',

        'total',

        'estado'

    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
    public function venta()
{
    return $this->hasOne(Venta::class);
}
}
