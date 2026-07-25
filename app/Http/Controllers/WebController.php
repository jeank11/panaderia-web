<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class WebController extends Controller
{
    public function inicio()
    {
        $productos = Producto::where('estado', 1)
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        return view('web.inicio', compact('productos'));
    }
}
