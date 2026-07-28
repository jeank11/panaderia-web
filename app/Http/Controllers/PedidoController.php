<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Carbon\Carbon;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{

    /**
     * Lista pedidos administrador
     */
    public function index()
    {
        $pedidos = Pedido::with([
            'cliente',
            'detalles.producto'
        ])
        ->latest()
        ->paginate(10);


        return view(
            'pedidos.index',
            compact('pedidos')
        );

    }


    /**
     * Mostrar detalle de un pedido
     */
    public function show(Pedido $pedido)
    {

        $pedido->load([
            'cliente',
            'detalles.producto'
        ]);


        return view(
            'pedidos.show',
            compact('pedido')
        );

    }



    /**
     * Mostrar formulario de confirmación cliente
     */
    public function confirmar()
    {

        if(!session()->has('cliente_id')){

            return redirect()
                ->route('clientes.login');

        }


        $carrito = session()->get('carrito', []);


        if(count($carrito) == 0){

            return redirect()
                ->route('carrito.index');

        }


        $cliente = \App\Models\Cliente::find(
            session('cliente_id')
        );


        return view(
            'pedidos.confirmar',
            compact(
                'carrito',
                'cliente'
            )
        );

    }



    /**
     * Guardar pedido
     */
    public function guardar(Request $request)
    {

        $request->validate([

            'fecha_entrega'=>'required|date',

            'hora_entrega'=>'required',

            'tipo_entrega'=>'required',

        ]);



        $carrito = session()->get('carrito', []);



        if(count($carrito)==0){

            return redirect()
                ->route('carrito.index');

        }



        $total = 0;


        foreach($carrito as $item){

            $total += 
            $item['precio'] * $item['cantidad'];

        }



        $pedido = Pedido::create([

            'codigo'=>'PED-'.str_pad(
                Pedido::count()+1,
                6,
                '0',
                STR_PAD_LEFT
            ),

            'cliente_id'=>session('cliente_id'),

            'fecha_pedido'=>Carbon::now(),

            'fecha_entrega'=>$request->fecha_entrega,

            'hora_entrega'=>$request->hora_entrega,

            'tipo_entrega'=>$request->tipo_entrega,

            'direccion_entrega'=>$request->direccion_entrega,

            'observaciones'=>$request->observaciones,

            'total'=>$total,

            'estado'=>'Pendiente'

        ]);




        foreach($carrito as $item){


            DetallePedido::create([

                'pedido_id'=>$pedido->id,

                'producto_id'=>$item['id'],

                'cantidad'=>$item['cantidad'],

                'precio'=>$item['precio'],

                'subtotal'=>
                $item['precio'] *
                $item['cantidad']

            ]);


        }



        session()->forget('carrito');



        return redirect()
            ->route('clientes.perfil')
            ->with(
                'success',
                'Pedido realizado correctamente.'
            );

    }
    public function cambiarEstado(Request $request, Pedido $pedido)
{
    $request->validate([
        'estado' => 'required'
    ]);


    $pedido->estado = $request->estado;

    $pedido->save();



    /*
    |--------------------------------------------------------------------------
    | Crear venta cuando el pedido pasa a Entregado
    |--------------------------------------------------------------------------
    */


   if(
    $request->estado == 'Entregado'
    &&
    !Venta::where('pedido_id',$pedido->id)->exists()
){


        $venta = Venta::create([

            'user_id' => Auth::id(),

            'cliente_id' => $pedido->cliente_id,

            'pedido_id' => $pedido->id,

            'fecha' => Carbon::now(),

            'total' => $pedido->total,

            'estado' => true

        ]);



        foreach($pedido->detalles as $detalle){


            DetalleVenta::create([

                'venta_id' => $venta->id,

                'producto_id' => $detalle->producto_id,

                'cantidad' => $detalle->cantidad,

                'precio' => $detalle->precio,

                'subtotal' => $detalle->subtotal

            ]);

        }


    }



    return redirect()
        ->route('pedidos.index')
        ->with(
            'success',
            'Estado actualizado correctamente.'
        );
}

}
