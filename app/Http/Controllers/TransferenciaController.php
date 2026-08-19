<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use App\Models\PagoCliente;
use Illuminate\Support\Facades\DB;

class TransferenciaController extends Controller
{
    /**
     * Mostrar transferencias
     */
    public function index()
    {
        $transferencias = Transferencia::with('cliente')
            ->orderByDesc('created_at')
            ->get();

        return view(
            'transferencias.index',
            compact('transferencias')
        );
    }


  /**
 * Aprobar transferencia
 */
public function aprobar(Transferencia $transferencia)
{
    /*
    |--------------------------------------------------------------------------
    | Verificar que la transferencia esté pendiente
    |--------------------------------------------------------------------------
    */

    if ($transferencia->estado !== 'pendiente') {

        return redirect()
            ->route('transferencias.index')
            ->with(
                'error',
                'Esta transferencia ya fue procesada.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Buscar cliente
    |--------------------------------------------------------------------------
    */

    $cliente = $transferencia->cliente;


    if (!$cliente) {

        return redirect()
            ->route('transferencias.index')
            ->with(
                'error',
                'El cliente de esta transferencia no existe.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Buscar ventas pendientes
    |--------------------------------------------------------------------------
    */

    $ventas = $cliente->ventas()
        ->where('tipo_pago', 'fiado')
        ->where('estado', true)
        ->whereIn('estado_pago', [
            'pendiente',
            'parcial'
        ])
        ->where('saldo_pendiente', '>', 0)
        ->orderBy('fecha', 'asc')
        ->orderBy('id', 'asc')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Calcular deuda total
    |--------------------------------------------------------------------------
    */

    $deudaTotal = $ventas->sum('saldo_pendiente');


    /*
    |--------------------------------------------------------------------------
    | Verificar deuda
    |--------------------------------------------------------------------------
    */

    if ($deudaTotal <= 0) {

        return redirect()
            ->route('transferencias.index')
            ->with(
                'error',
                'El cliente no tiene deuda pendiente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Verificar monto
    |--------------------------------------------------------------------------
    */

    if ($transferencia->monto > $deudaTotal) {

        return redirect()
            ->route('transferencias.index')
            ->with(
                'error',
                'El monto de la transferencia supera la deuda actual del cliente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Aplicar transferencia
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $transferencia,
        $ventas
    ) {

        /*
        |--------------------------------------------------------------------------
        | Crear UN SOLO pago
        |--------------------------------------------------------------------------
        */

        $pago = PagoCliente::create([

            'cliente_id' =>
                $transferencia->cliente_id,

            'venta_id' =>
                null,

            'monto' =>
                $transferencia->monto,

            'fecha' =>
                $transferencia->fecha_transferencia,

            'observacion' =>
                'Pago mediante transferencia bancaria. Referencia: '
                . $transferencia->referencia

        ]);


        /*
        |--------------------------------------------------------------------------
        | Monto disponible
        |--------------------------------------------------------------------------
        */

        $montoRestante =
            (float) $transferencia->monto;


        /*
        |--------------------------------------------------------------------------
        | Aplicar el pago a las ventas
        |--------------------------------------------------------------------------
        */

        foreach ($ventas as $venta) {

            if ($montoRestante <= 0) {

                break;
            }


            /*
            |------------------------------------------------------------------
            | Saldo actual
            |------------------------------------------------------------------
            */

            $saldo =
                (float) $venta->saldo_pendiente;


            /*
            |------------------------------------------------------------------
            | Monto aplicado
            |------------------------------------------------------------------
            */

            $montoAplicado =
                min(
                    $montoRestante,
                    $saldo
                );


            /*
            |------------------------------------------------------------------
            | Guardar relación entre pago y venta
            |------------------------------------------------------------------
            */

            $pago->ventas()->attach(
                $venta->id,
                [
                    'monto_aplicado' =>
                        $montoAplicado
                ]
            );


            /*
            |------------------------------------------------------------------
            | Calcular nuevo saldo
            |------------------------------------------------------------------
            */

            $nuevoSaldo =
                $saldo - $montoAplicado;


            /*
            |------------------------------------------------------------------
            | Actualizar venta
            |------------------------------------------------------------------
            */

            if ($nuevoSaldo <= 0) {

                $venta->saldo_pendiente = 0;

                $venta->estado_pago =
                    'pagada';

            } else {

                $venta->saldo_pendiente =
                    $nuevoSaldo;

                $venta->estado_pago =
                    'parcial';
            }


            $venta->save();


            /*
            |------------------------------------------------------------------
            | Restar dinero utilizado
            |------------------------------------------------------------------
            */

            $montoRestante -=
                $montoAplicado;
        }


        /*
        |--------------------------------------------------------------------------
        | Aprobar transferencia
        |--------------------------------------------------------------------------
        */

        $transferencia->estado =
            'aprobado';

        $transferencia->fecha_revision =
            now();

        $transferencia->save();

    });


    /*
    |--------------------------------------------------------------------------
    | Mensaje final
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('transferencias.index')
        ->with(
            'success',
            'Transferencia aprobada y pago aplicado correctamente.'
        );
}

/**
 * Rechazar transferencia
 */
public function rechazar(Transferencia $transferencia)
{
    /*
    |--------------------------------------------------------------------------
    | Verificar que esté pendiente
    |--------------------------------------------------------------------------
    */

    if ($transferencia->estado !== 'pendiente') {

        return redirect()
            ->route('transferencias.index')
            ->with(
                'error',
                'Esta transferencia ya fue procesada.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Rechazar transferencia
    |--------------------------------------------------------------------------
    */

    $transferencia->estado = 'rechazado';

    $transferencia->fecha_revision = now();

    $transferencia->save();


    /*
    |--------------------------------------------------------------------------
    | Mensaje
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('transferencias.index')
        ->with(
            'success',
            'Transferencia rechazada correctamente.'
        );
}
}
