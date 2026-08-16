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
public function pagoGlobal(Cliente $cliente)
{

    $ventas = $cliente->ventas()
        ->where('tipo_pago','fiado')
        ->where('estado',true)
        ->whereIn('estado_pago',[
            'pendiente',
            'parcial'
        ])
        ->get();


    if($ventas->count() == 0){

        return redirect()
            ->back()
            ->with(
                'error',
                'Este cliente no tiene deuda pendiente.'
            );

    }


    $totalPagado = $ventas->sum('saldo_pendiente');


    \DB::transaction(function() use(
        $cliente,
        $ventas,
        $totalPagado
    ){

        \App\Models\PagoCliente::create([

            'cliente_id'=>$cliente->id,

            'monto'=>$totalPagado,

            'fecha'=>now(),

            'observacion'=>'Pago total cuenta corriente'

        ]);



        foreach($ventas as $venta){

            $venta->estado_pago='pagada';

            $venta->saldo_pendiente=0;

            $venta->save();

        }


    });



    session()->put(
    'ventas_canceladas',
    $ventas->pluck('id')->toArray()
);


return redirect()

    ->route('clientes.recibo_pago',$cliente)

    ->with(
        'success',
        'Cuenta corriente cancelada correctamente.'
    );

}

}
