<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Venta;

class Cliente extends Authenticatable
{
    use Notifiable;


    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'telefono',
        'email',
        'password',
        'direccion',
        'fecha_nacimiento',
        'estado',
        'permite_fiado',
        'limite_credito'

    ];


    protected $hidden = [
        'password',
    ];


    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }


    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    
    public function pedidos()
{
    return $this->hasMany(Pedido::class);
}
public function getDeudaActualAttribute()
{
    return $this->ventas()
        ->where('estado_pago', 'pendiente')
        ->sum('saldo_pendiente');
}
public function getCreditoDisponibleAttribute()
{
    return $this->limite_credito - $this->deuda_actual;
}
}