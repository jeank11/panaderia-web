<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'estado'
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
}