<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClienteMiddleware
{
    /**
     * Verificar sesión del cliente
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!session()->has('cliente_id')) {

            return redirect()
                ->route('clientes.login')
                ->with(
                    'error',
                    'Debes iniciar sesión para acceder al portal.'
                );

        }


        return $next($request);
    }
}