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


    /*
    |--------------------------------------------------------------------------
    | Cliente
    |--------------------------------------------------------------------------
    */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Venta individual
    |--------------------------------------------------------------------------
    */

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Ventas asociadas al pago
    |--------------------------------------------------------------------------
    */

    public function ventas()
    {
        return $this->belongsToMany(
            Venta::class,
            'pago_cliente_venta',
            'pago_cliente_id',
            'venta_id'
        )->withPivot('monto_aplicado');
    }
}