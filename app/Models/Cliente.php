<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'telefono',
        'email',
        'direccion',
        'fecha_nacimiento',
        'estado'
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}
