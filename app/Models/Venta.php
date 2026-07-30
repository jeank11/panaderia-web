<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pedido;
use App\Models\PagoCliente;

class Venta extends Model
{
    protected $fillable = [
        'user_id',
        'cliente_id',
        'pedido_id',
        'fecha',
        'total',
        'estado',
        'tipo_pago',
        'estado_pago',
        'saldo_pendiente'
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pedido()
{
    return $this->belongsTo(Pedido::class);
}


    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function pagos()
{
    return $this->hasMany(PagoCliente::class);
}
}