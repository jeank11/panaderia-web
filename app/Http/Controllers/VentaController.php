<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    $ventas = \App\Models\Venta::with([
        'cliente',
        'usuario'
    ])
    ->orderBy('id','desc')
    ->paginate(10);


    return view('ventas.index', compact('ventas'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = \App\Models\Cliente::where('estado', true)
                ->orderBy('apellido')
                ->get();

$productos = \App\Models\Producto::where('estado', true)
                ->orderBy('nombre')
                ->get();

return view('ventas.create', compact('clientes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
   $request->validate([
    'cliente_id' => 'required',
    'productos' => 'required|array',
    'total' => 'required',
    'tipo_pago' => 'required|in:contado,fiado'
]);
$cliente = \App\Models\Cliente::findOrFail($request->cliente_id);

if ($request->tipo_pago == 'fiado' && !$cliente->permite_fiado) {

    return response()->json([
        'message' => 'Este cliente no está autorizado para comprar fiado.'
    ], 422);

}


    $venta = \App\Models\Venta::create([

    'user_id' => auth()->id(),

    'cliente_id' => $request->cliente_id,

    'fecha' => now(),

    'total' => $request->total,

    'tipo_pago' => $request->tipo_pago,

    'estado_pago' => $request->tipo_pago == 'contado'
                        ? 'pagada'
                        : 'pendiente',

    'saldo_pendiente' => $request->tipo_pago == 'contado'
                        ? 0
                        : $request->total,

    'estado' => true

]);


    foreach($request->productos as $producto)
    {

        \App\Models\DetalleVenta::create([

            'venta_id' => $venta->id,

            'producto_id' => $producto['producto_id'],

            'cantidad' => $producto['cantidad'],

            'precio' => $producto['precio'],

            'subtotal' => $producto['subtotal']

        ]);


        $item = \App\Models\Producto::find(
            $producto['producto_id']
        );


        $item->stock -= $producto['cantidad'];

        $item->save();

    }


    return response()->json([

        'mensaje' => 'Venta guardada correctamente',

        'venta_id' => $venta->id

    ]);
}

    /**
     * Display the specified resource.
     */
  public function show($id)
{
    $venta = \App\Models\Venta::with([
        'cliente',
        'usuario',
        'detalles.producto'
    ])
    ->findOrFail($id);


    return view('ventas.show', compact('venta'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function ticket($id)
{
    $venta = \App\Models\Venta::with([
        'cliente',
        'usuario',
        'detalles.producto'
    ])
    ->findOrFail($id);


    return view('ventas.ticket', compact('venta'));
}
public function anular(Venta $venta)
{

    // Evitar anular dos veces
    if (!$venta->estado) {

        return redirect()
            ->route('ventas.index')
            ->with('error','La venta ya está anulada.');

    }


    // Devolver productos al stock

    foreach($venta->detalles as $detalle)
    {

        $producto = $detalle->producto;


        $producto->stock += $detalle->cantidad;


        $producto->save();

    }


    // Cambiar estado de la venta

    $venta->estado = false;

    $venta->save();


    return redirect()
        ->route('ventas.index')
        ->with('success','Venta anulada correctamente y stock devuelto.');

}
}
