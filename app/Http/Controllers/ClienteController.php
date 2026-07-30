<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $clientes = \App\Models\Cliente::withSum([
        'ventas as deuda_actual' => function($query){

            $query->where('estado_pago','!=','pagada');

        }
    ], 'saldo_pendiente')
    ->orderBy('apellido')
    ->paginate(10);


    return view('clientes.index', compact('clientes'));
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
public function cuenta(Cliente $cliente)
{
    $ventas = $cliente->ventas()
        ->where('estado_pago','pendiente')
        ->get();


    $pagos = $cliente->pagos()
        ->orderBy('fecha','desc')
        ->get();


    return view('clientes.cuenta', compact(
        'cliente',
        'ventas',
        'pagos'
    ));
}
}
