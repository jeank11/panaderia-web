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



        $cliente = Cliente::with([

            'ventas' => function($query){

                $query->latest();

            }

        ])->find(
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
