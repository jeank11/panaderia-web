<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Venta;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Cliente::withSum([
        'ventas as deuda_actual' => function ($query) {
            $query->where('estado_pago', '!=', 'pagada');
        }
    ], 'saldo_pendiente');

    // Buscar
    if ($request->filled('buscar')) {

        $buscar = $request->buscar;

        $query->where(function ($q) use ($buscar) {

            $q->where('nombre', 'like', "%{$buscar}%")
              ->orWhere('apellido', 'like', "%{$buscar}%")
              ->orWhere('documento', 'like', "%{$buscar}%")
              ->orWhere('telefono', 'like', "%{$buscar}%")
              ->orWhere('email', 'like', "%{$buscar}%");

        });

    }

    // Estado
    if ($request->estado !== null && $request->estado !== '') {

        $query->where('estado', $request->estado);

    }

    // Permiso de fiado
    if ($request->permite_fiado !== null && $request->permite_fiado !== '') {

        $query->where(
            'permite_fiado',
            $request->permite_fiado
        );

    }

    $clientes = $query
        ->orderBy('apellido')
        ->paginate(10)
        ->withQueryString();

    return view(
        'clientes.index',
        compact('clientes')
    );
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|max:100',
        'apellido' => 'required|max:100',
        'documento' => 'required|max:20|unique:clientes',
        'telefono' => 'required|max:30',
        'email' => 'required|email|unique:clientes',
        'direccion' => 'nullable',
        'fecha_nacimiento' => 'nullable|date',
        'estado' => 'required|boolean',
        'permite_fiado' => 'required|boolean',
        'limite_credito' => 'required|numeric|min:0',
    ]);

    \App\Models\Cliente::create($request->all());

    return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(\App\Models\Cliente $cliente)
{
    return view('clientes.edit', compact('cliente'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, \App\Models\Cliente $cliente)
{
    $request->validate([
        'nombre' => 'required|max:100',
        'apellido' => 'required|max:100',
        'documento' => 'required|max:20|unique:clientes,documento,' . $cliente->id,
        'telefono' => 'required|max:30',
        'email' => 'required|email|unique:clientes,email,' . $cliente->id,
        'direccion' => 'nullable',
        'fecha_nacimiento' => 'nullable|date',
        'estado' => 'required|boolean',
        'permite_fiado' => 'required|boolean',
        'limite_credito' => 'required|numeric|min:0',
    ]);

    $cliente->update($request->all());

    return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function cambiarEstado(\App\Models\Cliente $cliente)
{
    $cliente->estado = !$cliente->estado;

    $cliente->save();

    return redirect()
            ->route('clientes.index')
            ->with('success', 'Estado del cliente actualizado correctamente.');
}

public function cuenta(Request $request, Cliente $cliente)
{
    /*
    |--------------------------------------------------------------------------
    | Buscador
    |--------------------------------------------------------------------------
    */

    $buscar = $request->buscar;


    /*
    |--------------------------------------------------------------------------
    | Ventas pendientes
    |--------------------------------------------------------------------------
    */

    $ventas = $cliente->ventas()
        ->whereIn('estado_pago', [
            'pendiente',
            'parcial'
        ])
        ->where('saldo_pendiente', '>', 0)
        ->when($buscar, function ($query) use ($buscar) {

            $query->where(function ($q) use ($buscar) {

                $q->where('id', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('fecha', 'LIKE', '%' . $buscar . '%');

            });

        })
        ->with([
            'detalles.producto'
        ])
        ->orderBy('fecha', 'desc')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Historial de pagos
    |--------------------------------------------------------------------------
    */

   $pagos = $cliente->pagos()
    ->with([
        'venta.detalles.producto',
        'ventas.detalles.producto'

    ])
    ->orderBy('fecha', 'desc')
    ->orderBy('created_at', 'desc')
    ->get();


    return view(
        'clientes.cuenta',
        compact(
            'cliente',
            'ventas',
            'pagos',
            'buscar'
        )
    );
}


public function reciboPago(Cliente $cliente)
{
    $ids = session('ventas_canceladas', []);

    /*
    |--------------------------------------------------------------------------
    | Buscar las ventas que fueron canceladas
    |--------------------------------------------------------------------------
    */

    $ventas = Venta::with([
        'detalles.producto'
    ])
    ->whereIn('id', $ids)
    ->get();


    /*
    |--------------------------------------------------------------------------
    | Buscar el pago realizado para estas ventas
    |--------------------------------------------------------------------------
    */

    $pago = \App\Models\PagoCliente::where(
        'cliente_id',
        $cliente->id
    )
    ->where(function ($query) use ($ids) {

        $query->whereIn('venta_id', $ids)
              ->orWhereNull('venta_id');

    })
    ->latest()
    ->first();


    /*
    |--------------------------------------------------------------------------
    | Monto realmente pagado
    |--------------------------------------------------------------------------
    */

    $totalPagado = $pago
        ? $pago->monto
        : 0;


    return view(
        'clientes.recibo_pago',
        compact(
            'cliente',
            'ventas',
            'pago',
            'totalPagado'
        )
    );
}
}
