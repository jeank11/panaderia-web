<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoCliente extends Model
{
    protected $table = 'pagos_cliente';

    protected $fillable = [
        'cliente_id',
        'venta_id',
        'monto',
        'fecha',
        'observacion'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }


    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
