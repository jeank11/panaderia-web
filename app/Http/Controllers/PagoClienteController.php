<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PagoCliente;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class PagoClienteController extends Controller
{

    public function store(Request $request, Cliente $cliente)
    {

        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string'
        ]);


        DB::transaction(function () use ($request, $cliente) {


            // Registrar el pago
            PagoCliente::create([

                'cliente_id' => $cliente->id,
                'venta_id' => $request->venta_id,
                'monto' => $request->monto,
                'fecha' => $request->fecha,
                'observacion' => $request->observacion

            ]);


            // Buscar la venta
            $venta = Venta::findOrFail($request->venta_id);

            // Verificar que el pago no supere la deuda

if ($request->monto > $venta->saldo_pendiente) {

    abort(redirect()
        ->route('clientes.cuenta', $cliente)
        ->with('error', 'El pago no puede superar el saldo pendiente.')
    );

}


            // Actualizar saldo
            $nuevoSaldo = $venta->saldo_pendiente - $request->monto;


            if ($nuevoSaldo <= 0) {

                $venta->saldo_pendiente = 0;
                $venta->estado_pago = 'pagada';

            } else {

                $venta->saldo_pendiente = $nuevoSaldo;

            }


            $venta->save();


        });


        return redirect()
            ->route('clientes.cuenta', $cliente)
            ->with('success','Pago registrado correctamente.');

    }

    public function cancelar(Cliente $cliente, Venta $venta)
{

    $monto = $venta->saldo_pendiente;


    if($monto <= 0){

        return redirect()
            ->route('clientes.cuenta',$cliente)
            ->with('error','Esta venta ya está cancelada.');

    }


    DB::transaction(function () use ($cliente, $venta, $monto) {


        PagoCliente::create([

            'cliente_id' => $cliente->id,

            'venta_id' => $venta->id,

            'monto' => $monto,

            'fecha' => now(),

            'observacion' => 'Cancelación total de deuda'

        ]);



        $venta->saldo_pendiente = 0;

        $venta->estado_pago = 'pagada';

        $venta->save();


    });



    return redirect()
        ->route('clientes.cuenta',$cliente)
        ->with('success','Deuda cancelada correctamente.');

}
public function pagoGlobal(Request $request, Cliente $cliente)
{
    /*
    |--------------------------------------------------------------------------
    | Validar pago
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'monto' =>
            'required|numeric|min:0.01',

        'fecha' =>
            'required|date',

        'observacion' =>
            'nullable|string|max:255',

    ]);


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
    | Verificar que existan deudas
    |--------------------------------------------------------------------------
    */

    if ($ventas->count() == 0) {

        return redirect()
            ->back()
            ->with(
                'error',
                'Este cliente no tiene deuda pendiente.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Calcular deuda total
    |--------------------------------------------------------------------------
    */

    $deudaTotal = $ventas->sum(
        'saldo_pendiente'
    );


    /*
    |--------------------------------------------------------------------------
    | Verificar que el pago no supere la deuda
    |--------------------------------------------------------------------------
    */

    if ($request->monto > $deudaTotal) {

        return redirect()
            ->back()
            ->with(
                'error',
                'El pago no puede superar la deuda actual de $'
                . number_format($deudaTotal, 2)
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Monto restante
    |--------------------------------------------------------------------------
    */

    $montoRestante =
        (float) $request->monto;


    /*
    |--------------------------------------------------------------------------
    | Ventas afectadas
    |--------------------------------------------------------------------------
    */

    $ventasAfectadas = [];


    /*
    |--------------------------------------------------------------------------
    | Registrar pago y distribuirlo
    |--------------------------------------------------------------------------
    */

    $pago = DB::transaction(function () use (
        $cliente,
        $ventas,
        $request,
        &$montoRestante,
        &$ventasAfectadas
    ) {

        /*
        |--------------------------------------------------------------------------
        | Determinar observación
        |--------------------------------------------------------------------------
        */

        $observacion =
            $request->observacion;


        if (!$observacion) {

            $observacion =
                'Pago de cuenta corriente';

        }


        /*
        |--------------------------------------------------------------------------
        | Crear el pago principal
        |--------------------------------------------------------------------------
        */

        $pago = PagoCliente::create([

            'cliente_id' =>
                $cliente->id,

            'monto' =>
                $request->monto,

            'fecha' =>
                $request->fecha,

            'observacion' =>
                $observacion

        ]);


        /*
        |--------------------------------------------------------------------------
        | Distribuir el pago entre las ventas
        |--------------------------------------------------------------------------
        */

        foreach ($ventas as $venta) {

            if ($montoRestante <= 0) {

                break;

            }


            /*
            |--------------------------------------------------------------
            | Saldo actual de la venta
            |--------------------------------------------------------------
            */

            $saldo =
                (float) $venta->saldo_pendiente;


            /*
            |--------------------------------------------------------------
            | Calcular cuánto se aplica
            |--------------------------------------------------------------
            */

            $pagoAplicado =
                min(
                    $montoRestante,
                    $saldo
                );


            /*
            |--------------------------------------------------------------
            | Calcular nuevo saldo
            |--------------------------------------------------------------
            */

            $nuevoSaldo =
                $saldo - $pagoAplicado;


            /*
            |--------------------------------------------------------------
            | Actualizar estado de la venta
            |--------------------------------------------------------------
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
            |--------------------------------------------------------------
            | Relacionar el pago con la venta
            |--------------------------------------------------------------
            */

            $pago->ventas()->attach(

                $venta->id,

                [
                    'monto_aplicado' =>
                        $pagoAplicado
                ]

            );


            /*
            |--------------------------------------------------------------
            | Guardar venta afectada
            |--------------------------------------------------------------
            */

            $ventasAfectadas[] =
                $venta->id;


            /*
            |--------------------------------------------------------------
            | Restar monto utilizado
            |--------------------------------------------------------------
            */

            $montoRestante -=
                $pagoAplicado;

        }


        return $pago;

    });


    /*
    |--------------------------------------------------------------------------
    | Guardar ventas afectadas para el recibo
    |--------------------------------------------------------------------------
    */

    session()->put(
        'ventas_canceladas',
        $ventasAfectadas
    );


    /*
    |--------------------------------------------------------------------------
    | Determinar mensaje
    |--------------------------------------------------------------------------
    */

    if ($montoRestante <= 0) {

        $mensaje =
            'Pago registrado correctamente.';

    } else {

        $mensaje =
            'Pago parcial registrado correctamente.';

    }


    /*
    |--------------------------------------------------------------------------
    | Redireccionar al recibo
    |--------------------------------------------------------------------------
    */

    return redirect()

        ->route(
            'clientes.recibo_pago',
            $cliente
        )

        ->with(
            'success',
            $mensaje
        );
}
public function detalle(PagoCliente $pago)
{
    $pago->load([
        'cliente',
        'ventas.detalles.producto'
    ]);


    $ventas = $pago->ventas->map(function ($venta) use ($pago) {

        /*
        |--------------------------------------------------------------------------
        | Monto aplicado de este pago a esta venta
        |--------------------------------------------------------------------------
        */

        $montoAplicado = $venta->pivot->monto_aplicado;


        return [

            'id' =>
                $venta->id,

            'fecha' =>
                \Carbon\Carbon::parse(
                    $venta->fecha
                )->format('d/m/Y H:i'),

            'total' =>
                (float) $venta->total,

            'saldo_pendiente' =>
                (float) $venta->saldo_pendiente,

            'estado_pago' =>
                $venta->estado_pago,

            'monto_aplicado' =>
                (float) $montoAplicado,

            'detalles' =>
                $venta->detalles->map(function ($detalle) {

                    return [

                        'producto' => $detalle->producto
                            ? [
                                'id' =>
                                    $detalle->producto->id,

                                'nombre' =>
                                    $detalle->producto->nombre
                            ]
                            : null,

                        'cantidad' =>
                            (float) $detalle->cantidad,

                        'precio' =>
                            (float) $detalle->precio,

                        'subtotal' =>
                            (float) $detalle->subtotal,

                    ];

                })->values()

        ];

    })->values();


    return response()->json([

        'id' =>
            $pago->id,

        'fecha' =>
            $pago->created_at
                ? $pago->created_at->format('d/m/Y H:i')
                : $pago->fecha,

        'monto' =>
            (float) $pago->monto,

        'observacion' =>
            $pago->observacion,

        'cliente' => $pago->cliente
            ? [
                'id' =>
                    $pago->cliente->id,

                'nombre' =>
                    $pago->cliente->nombre,

                'apellido' =>
                    $pago->cliente->apellido,
            ]
            : null,

        'ventas' =>
            $ventas,

    ]);
}

}
