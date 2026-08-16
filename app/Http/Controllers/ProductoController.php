<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{

   public function index(Request $request)
{
    $query = Producto::with('categoria');

    // Buscar por código o nombre
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;

        $query->where(function ($q) use ($buscar) {
            $q->where('codigo', 'like', "%{$buscar}%")
              ->orWhere('nombre', 'like', "%{$buscar}%");
        });
    }

    // Filtrar por categoría
    if ($request->filled('categoria')) {
        $query->where('categoria_id', $request->categoria);
    }

    // Filtrar por estado
    if ($request->estado !== null && $request->estado !== '') {
        $query->where('estado', $request->estado);
    }

    $productos = $query
        ->orderBy('nombre')
        ->paginate(10)
        ->withQueryString();

    $categorias = Categoria::where('estado', true)
        ->orderBy('nombre')
        ->get();

    return view('productos.index', compact(
        'productos',
        'categorias'
    ));
}



    public function create()
    {
        $categorias = Categoria::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('productos.create', compact('categorias'));
    }



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

            'imagen' => 'nullable|image|max:2048',

        ]);



        $imagen = null;


        if($request->hasFile('imagen')){

            $imagen = $request
                ->file('imagen')
                ->store('productos','public');

        }



        Producto::create([

            'codigo' => $request->codigo,

            'nombre' => $request->nombre,

            'categoria_id' => $request->categoria_id,

            'precio_compra' => $request->precio_compra,

            'precio_venta' => $request->precio_venta,

            'stock' => $request->stock,

            'stock_minimo' => $request->stock_minimo,

            'imagen' => $imagen,

            'estado' => true,

        ]);



        return redirect()

            ->route('productos.index')

            ->with(
                'success',
                'Producto registrado correctamente.'
            );

    }




    public function edit(Producto $producto)
    {

        $categorias = Categoria::where('estado', true)
            ->orderBy('nombre')
            ->get();


        return view(
            'productos.edit',
            compact(
                'producto',
                'categorias'
            )
        );

    }




    public function update(Request $request, Producto $producto)
    {


        $request->validate([

            'codigo' => 'required|unique:productos,codigo,'.$producto->id,

            'nombre' => 'required|max:255',

            'categoria_id' => 'required|exists:categorias,id',

            'precio_compra' => 'required|numeric|min:0',

            'precio_venta' => 'required|numeric|min:0',

            'stock' => 'required|integer|min:0',

            'stock_minimo' => 'required|integer|min:0',

            'imagen' => 'nullable|image|max:2048',

        ]);




        $imagen = $producto->imagen;



        if($request->hasFile('imagen')){


            if($producto->imagen){

                Storage::disk('public')
                    ->delete($producto->imagen);

            }



            $imagen = $request
                ->file('imagen')
                ->store('productos','public');

        }




        $producto->update([


            'codigo' => $request->codigo,

            'nombre' => $request->nombre,

            'categoria_id' => $request->categoria_id,

            'precio_compra' => $request->precio_compra,

            'precio_venta' => $request->precio_venta,

            'stock' => $request->stock,

            'stock_minimo' => $request->stock_minimo,

            'imagen' => $imagen,


        ]);




        return redirect()

            ->route('productos.index')

            ->with(
                'success',
                'Producto actualizado correctamente.'
            );

    }





    public function destroy(Producto $producto)
    {


        if($producto->imagen){

            Storage::disk('public')
                ->delete($producto->imagen);

        }



        $producto->delete();



        return redirect()

            ->route('productos.index')

            ->with(
                'success',
                'Producto eliminado correctamente.'
            );

    }






    public function show(Producto $producto)
    {
        //
    }
    public function detalle(Producto $producto)
{
    return view(
        'web.producto',
        compact('producto')
    );
}
public function cambiarEstado(Producto $producto)
{
    $producto->update([
        'estado' => !$producto->estado
    ]);

    return redirect()
        ->route('productos.index')
        ->with(
            'success',
            'Estado del producto actualizado correctamente.'
        );
}
}
