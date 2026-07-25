<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteRegistroController extends Controller
{

public function create()
{
    return view('clientes.registro');
}



    public function store(Request $request)
    {

       $request->validate([

    'nombre' => 'required',
    'apellido' => 'required',
    'documento' => 'required|unique:clientes,documento',
    'email' => 'required|email|unique:clientes,email',
    'password' => 'required|min:6|confirmed',

]);



        Cliente::create([

    'nombre' => $request->nombre,
    'apellido' => $request->apellido,
    'documento' => $request->documento,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'telefono' => $request->telefono,
    'direccion' => $request->direccion,
    'estado' => true,

]);



        return redirect()
            ->route('clientes.login')
            ->with(
                'success',
                'Cuenta creada correctamente. Ya puedes ingresar.'
            );

    }

}
