<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    protected $fillable = [
        'cliente_id',
        'monto',
        'fecha_transferencia',
        'referencia',
        'comprobante',
        'estado',
        'observacion',
    ];

    protected $casts = [
        'fecha_transferencia' => 'date',
        'monto' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}