<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;


class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index(Request $request)
{
    $query = Categoria::withCount('productos');

    // Buscar por nombre o descripción
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;

        $query->where(function ($q) use ($buscar) {
            $q->where('nombre', 'like', "%{$buscar}%")
              ->orWhere('descripcion', 'like', "%{$buscar}%");
        });
    }

    // Filtrar por estado
    if ($request->estado !== null && $request->estado !== '') {
        $query->where('estado', $request->estado);
    }

    $categorias = $query
        ->orderBy('nombre')
        ->paginate(10)
        ->withQueryString();

    return view('categorias.index', compact('categorias'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|max:100'
    ]);

    Categoria::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'estado' => true
    ]);

    return redirect()->route('categorias.index')
        ->with('success', 'Categoría creada correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
{
    return view('categorias.edit', compact('categoria'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
{
    $request->validate([
        'nombre' => 'required|max:100',
        'descripcion' => 'nullable|max:255',
    ]);

    $categoria->update([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
    ]);

    return redirect()
        ->route('categorias.index')
        ->with(
            'success',
            'Categoría actualizada correctamente.'
        );
}

public function cambiarEstado(Categoria $categoria)
{
    $categoria->update([
        'estado' => !$categoria->estado
    ]);

    return redirect()
        ->route('categorias.index')
        ->with(
            'success',
            'Estado de la categoría actualizado correctamente.'
        );
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
