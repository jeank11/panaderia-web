<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class ClienteAuthController extends Controller
{
    /**
     * Mostrar formulario de login del cliente.
     */
    public function showLogin()
    {
        return view('clientes.login');
    }

    /**
     * Procesar inicio de sesión.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $cliente = Cliente::where('email', $request->email)->first();

        if (
            $cliente &&
            Hash::check($request->password, $cliente->password)
        ) {

            session([
                'cliente_id' => $cliente->id
            ]);

            return redirect('/portal/perfil');
        }

        return back()->with(
            'error',
            'Correo o contraseña incorrectos.'
        );
    }
public function perfil()
{
    $cliente = Cliente::with([
        'ventas' => function ($query) {

            $query->latest();

        }

    ])->find(session('cliente_id'));

    if (!$cliente) {

        return redirect()->route('clientes.login');

    }

    return view(
        'clientes.perfil',
        compact('cliente')
    );
}
}
