<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Categoria;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $productos = Producto::with('categoria')
                    ->orderBy('nombre')
                    ->paginate(10);

    return view('productos.index', compact('productos'));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $categorias = Categoria::where('estado', true)
                    ->orderBy('nombre')
                    ->get();

    return view('productos.create', compact('categorias'));
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'codigo' => 'required|unique:productos,codigo',
        'nombre' => 'required|max:255',
        'categoria_id' => 'required|exists:categorias,id',
        'precio_compra' => 'required|numeric|min:0',
        'precio_venta' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'stock_minimo' => 'required|integer|min:0',
    ]);

    Producto::create([
        'codigo' => $request->codigo,
        'nombre' => $request->nombre,
        'categoria_id' => $request->categoria_id,
        'precio_compra' => $request->precio_compra,
        'precio_venta' => $request->precio_venta,
        'stock' => $request->stock,
        'stock_minimo' => $request->stock_minimo,
        'estado' => true,
    ]);

    return redirect()
        ->route('productos.index')
        ->with('success', 'Producto registrado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
