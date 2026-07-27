<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class ClienteAuthController extends Controller
{


    /**
     * Mostrar login cliente
     */
    public function showLogin()
    {
        return view('clientes.login');
    }



    /**
     * Login cliente
     */
    public function login(Request $request)
    {

        $request->validate([

            'email' => 'required|email',

            'password' => 'required'

        ]);



        $cliente = Cliente::where(
            'email',
            $request->email
        )->first();



        if(
            $cliente &&
            Hash::check(
                $request->password,
                $cliente->password
            )
        ){


           $request->session()->regenerate();

session([
    'cliente_id' => $cliente->id
]);



            return redirect()
                ->route('clientes.perfil');


        }



        return back()
            ->with(
                'error',
                'Correo o contraseña incorrectos.'
            );

    }





    /**
     * Perfil del cliente
     */
    public function perfil()
    {

        if(!session()->has('cliente_id')){

            return redirect()
                ->route('clientes.login');

        }



        $cliente = Cliente::find(
    session('cliente_id')
);



        if(!$cliente){

            session()->forget('cliente_id');


            return redirect()
                ->route('clientes.login');

        }



        return view(
            'clientes.perfil',
            compact('cliente')
        );

    }

    public function compras()
{

    if(!session()->has('cliente_id')){

        return redirect()
            ->route('clientes.login');

    }


    $cliente = Cliente::with([

        'ventas.detalles.producto'

    ])->find(
        session('cliente_id')
    );


    return view(
        'clientes.compras',
        compact('cliente')
    );

}



public function detalleCompra($id)
{

    $clienteId = session('cliente_id');


    $venta = \App\Models\Venta::with([
        'detalles.producto'
    ])
    ->where('cliente_id',$clienteId)
    ->where('id',$id)
    ->firstOrFail();



    return view(
        'clientes.detalle_compra',
        compact('venta')
    );

}
public function editarPerfil()
{

    $cliente = Cliente::find(
        session('cliente_id')
    );


    return view(
        'clientes.editar_perfil',
        compact('cliente')
    );

}



public function actualizarPerfil(Request $request)
{

    $cliente = Cliente::find(
        session('cliente_id')
    );


    $request->validate([

        'nombre' => 'required',
        'apellido' => 'required',
        'telefono' => 'required',
        'direccion' => 'required'

    ]);


    $cliente->update([

        'nombre' => $request->nombre,

        'apellido' => $request->apellido,

        'telefono' => $request->telefono,

        'direccion' => $request->direccion,

        'fecha_nacimiento' => $request->fecha_nacimiento

    ]);


    return redirect()
        ->route('clientes.perfil')
        ->with(
            'success',
            'Perfil actualizado correctamente.'
        );

}

public function formPassword()
{

    return view(
        'clientes.cambiar_password'
    );

}



public function cambiarPassword(Request $request)
{

    $request->validate([

        'password_actual' => 'required',

        'password' => 'required|min:8|confirmed'

    ]);



    $cliente = Cliente::find(
        session('cliente_id')
    );



    if(!Hash::check(
        $request->password_actual,
        $cliente->password
    )){

        return back()
            ->with(
                'error',
                'La contraseña actual no es correcta.'
            );

    }



    $cliente->password = Hash::make(
        $request->password
    );


    $cliente->save();



    return redirect()
        ->route('clientes.perfil')
        ->with(
            'success',
            'Contraseña actualizada correctamente.'
        );

}





    /**
     * Cerrar sesión cliente
     */
    public function logout()
    {

        session()->forget('cliente_id');


        return redirect()
            ->route('clientes.login')
            ->with(
                'success',
                'Sesión cerrada correctamente.'
            );

    }


}
