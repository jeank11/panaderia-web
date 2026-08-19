<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use App\Models\PagoCliente;
use App\Models\Transferencia;

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
public function estadoCuenta()
{
    $cliente = \App\Models\Cliente::find(
        session('cliente_id')
    );

    if (!$cliente) {

        session()->forget('cliente_id');

        return redirect()
            ->route('clientes.login');
    }


    $ventas = $cliente->ventas()
        ->with('detalles.producto')
        ->where('tipo_pago', 'fiado')
        ->where('estado', 1)
        ->where('saldo_pendiente', '>', 0)
        ->whereIn('estado_pago', ['pendiente', 'parcial'])
        ->orderBy('fecha', 'desc')
        ->get();


    $deuda = $ventas->sum('saldo_pendiente');


    $pagos = $cliente->pagos()
    ->orderBy('created_at', 'desc')
    ->get();


    /*
    |--------------------------------------------------------------------------
    | Transferencias realizadas
    |--------------------------------------------------------------------------
    */

    $transferencias = \App\Models\Transferencia::where(
        'cliente_id',
        $cliente->id
    )
    ->orderByDesc('fecha_transferencia')
    ->orderByDesc('id')
    ->get();


    return view(
        'clientes.estado_cuenta',
        compact(
            'cliente',
            'ventas',
            'deuda',
            'pagos',
            'transferencias'
        )
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

public function pedidos()
{
    $cliente = Cliente::find(
        session('cliente_id')
    );


    $pedidos = \App\Models\Pedido::with([
        'detalles.producto'
    ])
    ->where(
        'cliente_id',
        $cliente->id
    )
    ->latest()
    ->paginate(10);


    return view(
        'clientes.pedidos',
        compact(
            'cliente',
            'pedidos'
        )
    );
}

public function compras(Request $request)
{
    $cliente = Cliente::find(
        session('cliente_id')
    );


    if (!$cliente) {

        session()->forget('cliente_id');

        return redirect()
            ->route('clientes.login');

    }


    /*
    |--------------------------------------------------------------------------
    | Buscar compras del cliente
    |--------------------------------------------------------------------------
    */

    $query = $cliente->ventas()
        ->where('estado', true)
        ->with([
            'detalles.producto',
            'pedido'
        ]);


    /*
    |--------------------------------------------------------------------------
    | Buscar por producto o número de compra
    |--------------------------------------------------------------------------
    */

    if ($request->filled('buscar')) {

        $buscar = $request->buscar;

        $query->where(function ($q) use ($buscar) {

            // Buscar por número de compra
            if (is_numeric($buscar)) {

                $q->where('id', $buscar);

            }


            // Buscar por nombre de producto
            $q->orWhereHas(
                'detalles.producto',
                function ($producto) use ($buscar) {

                    $producto->where(
                        'nombre',
                        'LIKE',
                        '%' . $buscar . '%'
                    );

                }
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Filtrar por tipo de pago
    |--------------------------------------------------------------------------
    */

    if (
        $request->filled('tipo_pago') &&
        in_array(
            $request->tipo_pago,
            ['contado', 'fiado']
        )
    ) {

        $query->where(
            'tipo_pago',
            $request->tipo_pago
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Filtrar fecha desde
    |--------------------------------------------------------------------------
    */

    if ($request->filled('fecha_desde')) {

        $query->whereDate(
            'fecha',
            '>=',
            $request->fecha_desde
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Filtrar fecha hasta
    |--------------------------------------------------------------------------
    */

    if ($request->filled('fecha_hasta')) {

        $query->whereDate(
            'fecha',
            '<=',
            $request->fecha_hasta
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Ordenar y paginar
    |--------------------------------------------------------------------------
    */

    $ventas = $query
    ->orderByDesc('fecha')
    ->orderByDesc('id')
    ->paginate(10)
    ->withQueryString();

    return view(
        'clientes.compras',
        compact(
            'cliente',
            'ventas'
        )
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

    public function detallePedido(\App\Models\Pedido $pedido)
{

    if ($pedido->cliente_id != session('cliente_id')) {

        abort(403);

    }

    $pedido->load([
        'detalles.producto'
    ]);

    return view(
        'clientes.detalle_pedido',
        compact('pedido')
    );

}
public function productos()
{
    $cliente = \App\Models\Cliente::find(
        session('cliente_id')
    );

    $productos = \App\Models\Producto::where('estado',1)
        ->where('stock','>',0)
        ->orderBy('nombre')
        ->get();

    return view(
        'clientes.productos',
        compact(
            'cliente',
            'productos'
        )
    );
}
public function detallePago($pago)
{
    if (!session()->has('cliente_id')) {

        return redirect()
            ->route('clientes.login');
    }


    $cliente = Cliente::find(
        session('cliente_id')
    );


    if (!$cliente) {

        session()->forget('cliente_id');

        return redirect()
            ->route('clientes.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Buscar el pago
    |--------------------------------------------------------------------------
    */

    $pago = PagoCliente::with([
        'cliente',
        'venta.detalles.producto',
        'ventas.detalles.producto'
    ])
    ->where('cliente_id', $cliente->id)
    ->findOrFail($pago);


    /*
    |--------------------------------------------------------------------------
    | Obtener ventas asociadas
    |--------------------------------------------------------------------------
    |
    | Primero intentamos con la relación belongsToMany.
    |
    | Si no existen registros en pago_cliente_venta,
    | usamos venta_id como respaldo.
    |
    */

    $ventas = $pago->ventas;


    if ($ventas->isEmpty() && $pago->venta) {

        $ventas = collect([
            $pago->venta
        ]);

    }


    return view(
        'clientes.detalle_pago',
        compact(
            'cliente',
            'pago',
            'ventas'
        )
    );
}

public function transferenciaCreate()
{
    $cliente = Cliente::find(
        session('cliente_id')
    );

    if (!$cliente) {

        session()->forget('cliente_id');

        return redirect()
            ->route('clientes.login');
    }

    return view(
        'clientes.transferencia',
        compact('cliente')
    );
}


public function transferenciaStore(Request $request)
{
   
    $cliente = Cliente::find(
        session('cliente_id')
    );

    if (!$cliente) {

        session()->forget('cliente_id');

        return redirect()
            ->route('clientes.login');
    }


    $request->validate([

        'monto' => [
            'required',
            'numeric',
            'min:0.01',
            'max:' . $cliente->deuda_actual
        ],

        'fecha_transferencia' => [
            'required',
            'date'
        ],

        'referencia' => [
            'required',
            'string',
            'max:255'
        ],

        'comprobante' => [
            'nullable',
            'file',
            'mimes:jpg,jpeg,png,pdf',
            'max:5120'
        ],

        'observacion' => [
            'nullable',
            'string',
            'max:1000'
        ]

    ]);


    $comprobante = null;


    if ($request->hasFile('comprobante')) {

        $comprobante = $request
            ->file('comprobante')
            ->store(
                'transferencias',
                'public'
            );
    }


    Transferencia::create([

        'cliente_id' =>
            $cliente->id,

        'monto' =>
            $request->monto,

        'fecha_transferencia' =>
            $request->fecha_transferencia,

        'referencia' =>
            $request->referencia,

        'comprobante' =>
            $comprobante,

        'estado' =>
            'pendiente',

        'observacion' =>
            $request->observacion

    ]);


    return redirect()
        ->route('clientes.estado_cuenta')
        ->with(
            'success',
            'Transferencia informada correctamente. Quedará pendiente de revisión.'
        );
}




}
