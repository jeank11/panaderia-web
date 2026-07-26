<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{

    /**
     * Mostrar carrito
     */
    public function index()
    {
        $carrito = session()->get('carrito', []);

        return view(
            'carrito.index',
            compact('carrito')
        );
    }

    /**
     * Agregar producto
     */
    public function agregar(Producto $producto)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$producto->id])) {

            if ($carrito[$producto->id]['cantidad'] < $producto->stock) {

                $carrito[$producto->id]['cantidad']++;

            } else {

                return back()->with(
                    'error',
                    'No hay más stock disponible.'
                );

            }

        } else {

            $carrito[$producto->id] = [

                'id' => $producto->id,

                'nombre' => $producto->nombre,

                'precio' => $producto->precio_venta,

                'cantidad' => 1,

                'stock' => $producto->stock,

                'imagen' => $producto->imagen

            ];

        }

        session()->put('carrito', $carrito);

        return back()->with(
            'success',
            'Producto agregado correctamente.'
        );
    }

    /**
     * Aumentar cantidad
     */
    public function aumentar(Producto $producto)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$producto->id])) {

            if ($carrito[$producto->id]['cantidad'] < $carrito[$producto->id]['stock']) {

                $carrito[$producto->id]['cantidad']++;

            }

        }

        session()->put('carrito', $carrito);

        return redirect()->route('carrito.index');
    }

    /**
     * Disminuir cantidad
     */
    public function disminuir(Producto $producto)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$producto->id])) {

            $carrito[$producto->id]['cantidad']--;

            if ($carrito[$producto->id]['cantidad'] <= 0) {

                unset($carrito[$producto->id]);

            }

        }

        session()->put('carrito', $carrito);

        return redirect()->route('carrito.index');
    }

    /**
     * Eliminar producto
     */
    public function eliminar(Producto $producto)
    {
        $carrito = session()->get('carrito', []);

        unset($carrito[$producto->id]);

        session()->put('carrito', $carrito);

        return redirect()->route('carrito.index');
    }

    /**
     * Vaciar carrito
     */
    public function vaciar()
    {
        session()->forget('carrito');

        return redirect()->route('carrito.index');
    }

}
