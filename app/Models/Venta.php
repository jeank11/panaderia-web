<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Venta extends Model
{
    protected $fillable = [
        'user_id',
        'cliente_id',
        'fecha',
        'total',
        'estado'
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }


    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}