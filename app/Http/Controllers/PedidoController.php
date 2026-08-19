<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{

    /**
     * ============================================================
     * LISTA DE PEDIDOS - ADMINISTRADOR
     * ============================================================
     */
public function index(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | FILTROS
    |--------------------------------------------------------------------------
    */

    $buscar = $request->buscar;

    $direccion = $request->direccion;

    $estado = $request->estado;

    $fecha = $request->fecha;


    /*
    |--------------------------------------------------------------------------
    | CONSULTA BASE
    |--------------------------------------------------------------------------
    */

    $consulta = Pedido::query()

        ->when($buscar, function ($query) use ($buscar) {

            $query->where(function ($q) use ($buscar) {

                $q->where(
                    'codigo',
                    'LIKE',
                    '%' . $buscar . '%'
                )

                ->orWhereHas('cliente', function ($cliente) use ($buscar) {

                    $cliente
                        ->where(
                            'nombre',
                            'LIKE',
                            '%' . $buscar . '%'
                        )
                        ->orWhere(
                            'apellido',
                            'LIKE',
                            '%' . $buscar . '%'
                        );

                });

            });

        })


        ->when($direccion, function ($query) use ($direccion) {

            $query->where(
                'direccion_entrega',
                'LIKE',
                '%' . $direccion . '%'
            );

        })


        ->when($estado, function ($query) use ($estado) {

            $query->where(
                'estado',
                $estado
            );

        })


        ->when($fecha, function ($query) use ($fecha) {

            $query->whereDate(
                'fecha_entrega',
                $fecha
            );

        });


    /*
    |--------------------------------------------------------------------------
    | PEDIDOS PARA LA TABLA
    |--------------------------------------------------------------------------
    */

    $pedidos = (clone $consulta)

        ->with([
            'cliente',
            'detalles.producto',
            'venta'
        ])

        ->latest()

        ->paginate(10)

        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | RESUMEN DE PRODUCTOS
    |--------------------------------------------------------------------------
    */

    $detalles = (clone $consulta)

        ->with('detalles.producto')

        ->get()

        ->pluck('detalles')

        ->flatten();


    $resumenProductos = $detalles

        ->groupBy('producto_id')

        ->map(function ($detallesProducto) {

            return [

                'producto' => $detallesProducto
                    ->first()
                    ->producto,

                'cantidad' => $detallesProducto
                    ->sum('cantidad'),

                'subtotal' => $detallesProducto
                    ->sum('subtotal'),

            ];

        })

        ->sortByDesc('cantidad')

        ->values();


    /*
    |--------------------------------------------------------------------------
    | ESTADÍSTICAS DEL FILTRO
    |--------------------------------------------------------------------------
    */

    $pedidosFiltrados = (clone $consulta)->count();


    $unidadesFiltradas = $detalles->sum(
        'cantidad'
    );


    $totalFiltrado = $detalles->sum(
        'subtotal'
    );


    /*
    |--------------------------------------------------------------------------
    | ESTADÍSTICAS GENERALES
    |--------------------------------------------------------------------------
    */

    $totalPedidos = Pedido::count();

    $pendientes = Pedido::where(
        'estado',
        'Pendiente'
    )->count();

    $preparando = Pedido::where(
        'estado',
        'Preparando'
    )->count();

    $listos = Pedido::where(
        'estado',
        'Listo'
    )->count();

    $entregados = Pedido::where(
        'estado',
        'Entregado'
    )->count();

    $cancelados = Pedido::where(
        'estado',
        'Cancelado'
    )->count();


    /*
    |--------------------------------------------------------------------------
    | VISTA
    |--------------------------------------------------------------------------
    */

    return view(
        'pedidos.index',
        compact(

            'pedidos',

            'totalPedidos',

            'pendientes',

            'preparando',

            'listos',

            'entregados',

            'cancelados',

            'resumenProductos',

            'pedidosFiltrados',

            'unidadesFiltradas',

            'totalFiltrado'

        )
    );
}



    /**
     * ============================================================
     * FORMULARIO NUEVO PEDIDO - ADMINISTRADOR
     * ============================================================
     */
public function create()
{
    $clientes = Cliente::where(
        'estado',
        true
    )
    ->orderBy('nombre')
    ->orderBy('apellido')
    ->get();


    $productos = Producto::where(
        'estado',
        true
    )
    ->orderBy('nombre')
    ->get();


    return view(
        'pedidos.create',
        compact(
            'clientes',
            'productos'
        )
    );
}



    /**
     * ============================================================
     * GUARDAR PEDIDO DESDE ADMINISTRACIÓN
     * ============================================================
     */
    public function store(Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'cliente_id' =>
                'required|exists:clientes,id',

            'fecha_entrega' =>
                'required|date',

            'hora_entrega' =>
                'required',

            'tipo_entrega' =>
                'required',

            'productos' =>
                'required|array|min:1',

            'productos.*.producto_id' =>
                'required|exists:productos,id',

            'productos.*.cantidad' =>
                'required|numeric|min:1',

        ]);



        /*
        |--------------------------------------------------------------------------
        | Calcular total
        |--------------------------------------------------------------------------
        */

        $total = 0;


        foreach (
            $request->productos
            as $item
        ) {

            $producto = Producto::find(
                $item['producto_id']
            );


            if (!$producto) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Uno de los productos seleccionados no existe.'
                    );

            }


            $cantidad =
                (int) $item['cantidad'];


            $total +=
                $producto->precio_venta *
                $cantidad;

        }



        /*
        |--------------------------------------------------------------------------
        | Generar código
        |--------------------------------------------------------------------------
        */

        $ultimoPedido =
            Pedido::latest('id')->first();


        $numero =
            $ultimoPedido
                ? $ultimoPedido->id + 1
                : 1;


        $codigo =
            'PED-' .
            str_pad(
                $numero,
                6,
                '0',
                STR_PAD_LEFT
            );



        /*
        |--------------------------------------------------------------------------
        | Crear pedido
        |--------------------------------------------------------------------------
        */

        $pedido = Pedido::create([

            'codigo' =>
                $codigo,

            'cliente_id' =>
                $request->cliente_id,

            'fecha_pedido' =>
                Carbon::now(),

            'fecha_entrega' =>
                $request->fecha_entrega,

            'hora_entrega' =>
                $request->hora_entrega,

            'tipo_entrega' =>
                $request->tipo_entrega,

            'direccion_entrega' =>
                $request->direccion_entrega,

            'observaciones' =>
                $request->observaciones,

            'total' =>
                $total,

            'estado' =>
                'Pendiente'

        ]);



        /*
        |--------------------------------------------------------------------------
        | Crear detalles del pedido
        |--------------------------------------------------------------------------
        */

        foreach (
            $request->productos
            as $item
        ) {

            $producto =
                Producto::find(
                    $item['producto_id']
                );


            $cantidad =
                (int) $item['cantidad'];


            $precio =
                $producto->precio_venta;


            DetallePedido::create([

                'pedido_id' =>
                    $pedido->id,

                'producto_id' =>
                    $producto->id,

                'cantidad' =>
                    $cantidad,

                'precio' =>
                    $precio,

                'subtotal' =>
                    $precio * $cantidad

            ]);

        }



        /*
        |--------------------------------------------------------------------------
        | Redireccionar al detalle
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'pedidos.show',
                $pedido
            )
            ->with(
                'success',
                'Pedido creado correctamente.'
            );
    }



    /**
     * ============================================================
     * MOSTRAR DETALLE DE UN PEDIDO
     * ============================================================
     */
    public function show(Pedido $pedido)
    {

        $pedido->load([
            'cliente',
            'detalles.producto'
        ]);


        return view(
            'pedidos.show',
            compact('pedido')
        );
    }



   /**
 * ============================================================
 * FORMULARIO CONFIRMACIÓN - CLIENTE
 * ============================================================
 */
public function confirmar()
{
    if (!session()->has('cliente_id')) {

        return redirect()
            ->route('clientes.login');
    }

    $carrito = session()->get('carrito', []);

    if (count($carrito) == 0) {

        return redirect()
            ->route('carrito.index');
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
    | Calcular deuda actual del cliente
    |--------------------------------------------------------------------------
    */

    $deudaActual = $cliente->ventas()
        ->where('tipo_pago', 'fiado')
        ->where('estado', true)
        ->where('saldo_pendiente', '>', 0)
        ->whereIn('estado_pago', [
            'pendiente',
            'parcial'
        ])
        ->sum('saldo_pendiente');


    /*
    |--------------------------------------------------------------------------
    | Calcular crédito disponible
    |--------------------------------------------------------------------------
    */

    $limiteCredito = (float) ($cliente->limite_credito ?? 0);

    $creditoDisponible = max(
        0,
        $limiteCredito - $deudaActual
    );


    return view(
        'pedidos.confirmar',
        compact(
            'carrito',
            'cliente',
            'deudaActual',
            'limiteCredito',
            'creditoDisponible'
        )
    );
}

   /**
 * ============================================================
 * GUARDAR PEDIDO - CLIENTE
 * ============================================================
 */
public function guardar(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Verificar sesión del cliente
    |--------------------------------------------------------------------------
    */

    if (!session()->has('cliente_id')) {

        return redirect()
            ->route('clientes.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Validar datos
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'fecha_entrega' =>
            'required|date',

        'hora_entrega' =>
            'required',

        'tipo_entrega' =>
            'required',

        'tipo_pago' =>
            'required|in:contado,fiado',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Obtener cliente
    |--------------------------------------------------------------------------
    */

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
    | Obtener carrito
    |--------------------------------------------------------------------------
    */

    $carrito = session()->get(
        'carrito',
        []
    );


    if (count($carrito) == 0) {

        return redirect()
            ->route('carrito.index')
            ->with(
                'error',
                'El carrito está vacío.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Calcular total
    |--------------------------------------------------------------------------
    */

    $total = 0;


    foreach ($carrito as $item) {

        $total +=
            $item['precio'] *
            $item['cantidad'];
    }


    /*
    |--------------------------------------------------------------------------
    | Verificar compra fiada
    |--------------------------------------------------------------------------
    */

    if ($request->tipo_pago === 'fiado') {

        /*
        |----------------------------------------------------------------------
        | Verificar permiso
        |----------------------------------------------------------------------
        */

        if (!$cliente->permite_fiado) {

            return back()
                ->with(
                    'error',
                    'Tu cuenta no está autorizada para realizar compras fiadas.'
                )
                ->withInput();
        }


        /*
        |----------------------------------------------------------------------
        | Calcular deuda actual
        |----------------------------------------------------------------------
        */

        $deudaActual = $cliente->ventas()
            ->where('tipo_pago', 'fiado')
            ->where('estado', true)
            ->where('saldo_pendiente', '>', 0)
            ->whereIn('estado_pago', [
                'pendiente',
                'parcial'
            ])
            ->sum('saldo_pendiente');


        /*
        |----------------------------------------------------------------------
        | Calcular crédito disponible
        |----------------------------------------------------------------------
        */

        $limiteCredito = (float) (
            $cliente->limite_credito ?? 0
        );


        $creditoDisponible = max(
            0,
            $limiteCredito - $deudaActual
        );


        /*
        |----------------------------------------------------------------------
        | Verificar límite
        |----------------------------------------------------------------------
        */

        if ($total > $creditoDisponible) {

            return back()
                ->with(
                    'error',
                    'No puedes realizar esta compra fiada. ' .
                    'Tu crédito disponible es de $' .
                    number_format(
                        $creditoDisponible,
                        2
                    ) .
                    ' y el pedido tiene un valor de $' .
                    number_format(
                        $total,
                        2
                    ) .
                    '.'
                )
                ->withInput();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Crear pedido
    |--------------------------------------------------------------------------
    */

    $pedido = Pedido::create([

        'codigo' =>
            'PED-' .
            str_pad(
                Pedido::count() + 1,
                6,
                '0',
                STR_PAD_LEFT
            ),

        'cliente_id' =>
            $cliente->id,

        'fecha_pedido' =>
            Carbon::now(),

        'fecha_entrega' =>
            $request->fecha_entrega,

        'hora_entrega' =>
            $request->hora_entrega,

        'tipo_entrega' =>
            $request->tipo_entrega,

        'direccion_entrega' =>
            $request->direccion_entrega,

        'observaciones' =>
            $request->observaciones,

        'total' =>
            $total,

        'tipo_pago' =>
            $request->tipo_pago,

        'estado' =>
            'Pendiente'

    ]);


    /*
    |--------------------------------------------------------------------------
    | Crear detalles del pedido
    |--------------------------------------------------------------------------
    */

    foreach ($carrito as $item) {

        DetallePedido::create([

            'pedido_id' =>
                $pedido->id,

            'producto_id' =>
                $item['id'],

            'cantidad' =>
                $item['cantidad'],

            'precio' =>
                $item['precio'],

            'subtotal' =>
                $item['precio'] *
                $item['cantidad']

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito
    |--------------------------------------------------------------------------
    */

    session()->forget('carrito');


    /*
    |--------------------------------------------------------------------------
    | Mensaje
    |--------------------------------------------------------------------------
    */

    $mensaje = $request->tipo_pago === 'fiado'

        ? 'Pedido realizado correctamente. La compra fue registrada como fiada.'

        : 'Pedido realizado correctamente.';


    return redirect()
        ->route('clientes.pedidos')
        ->with(
            'success',
            $mensaje
        );
}



    /**
     * ============================================================
     * CAMBIAR ESTADO
     * ============================================================
     */
    public function produccion(Request $request)
{
    $fecha = $request->fecha
        ?? Carbon::today()->format('Y-m-d');


    $direccion = $request->direccion;


    $pedidos = Pedido::with([
        'detalles.producto'
    ])
    ->whereDate(
        'fecha_entrega',
        $fecha
    )
    ->whereNotIn(
        'estado',
        [
            'Entregado',
            'Cancelado'
        ]
    )


    /*
    |--------------------------------------------------------------------------
    | Filtrar por dirección
    |--------------------------------------------------------------------------
    */

    ->when(
        $direccion,
        function ($query) use ($direccion) {

            $query->where(
                'direccion_entrega',
                'LIKE',
                '%' . $direccion . '%'
            );

        }
    )


    ->get();


    $produccion = [];


    foreach ($pedidos as $pedido) {

        foreach ($pedido->detalles as $detalle) {

            $productoId = $detalle->producto_id;


            if (!isset($produccion[$productoId])) {

                $produccion[$productoId] = [

                    'producto' => $detalle->producto,

                    'cantidad' => 0

                ];

            }


            $produccion[$productoId]['cantidad']
                += $detalle->cantidad;

        }

    }


    return view(
        'pedidos.produccion',
        compact(
            'produccion',
            'fecha',
            'direccion',
            'pedidos'
        )
    );
}
    public function imprimirProduccion(Request $request)
{
    $fecha = $request->fecha
        ?? Carbon::today()->format('Y-m-d');


    $direccion = $request->direccion;


    $pedidos = Pedido::with([
        'detalles.producto'
    ])
    ->whereDate(
        'fecha_entrega',
        $fecha
    )
    ->whereNotIn(
        'estado',
        [
            'Entregado',
            'Cancelado'
        ]
    )


    ->when(
        $direccion,
        function ($query) use ($direccion) {

            $query->where(
                'direccion_entrega',
                'LIKE',
                '%' . $direccion . '%'
            );

        }
    )


    ->get();


    $produccion = [];


    foreach ($pedidos as $pedido) {

        foreach ($pedido->detalles as $detalle) {

            $productoId = $detalle->producto_id;


            if (!isset($produccion[$productoId])) {

                $produccion[$productoId] = [

                    'producto' => $detalle->producto,

                    'cantidad' => 0

                ];

            }


            $produccion[$productoId]['cantidad']
                += $detalle->cantidad;

        }

    }


    return view(
        'pedidos.produccion_imprimir',
        compact(
            'produccion',
            'fecha',
            'direccion',
            'pedidos'
        )
    );
}
    
public function cambiarEstado(
    Request $request,
    Pedido $pedido
) {

    /*
    |--------------------------------------------------------------------------
    | Validar estado
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'estado' => 'required|string',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Si el pedido pasa a Entregado
    |--------------------------------------------------------------------------
    | En este momento obligamos a elegir:
    |
    | contado
    | fiado
    |
    */

    if ($request->estado === 'Entregado') {

        $request->validate([

            'tipo_pago' => 'required|in:contado,fiado',

        ]);

    }


    /*
    |--------------------------------------------------------------------------
    | Guardar tipo de pago en el pedido
    |--------------------------------------------------------------------------
    */

    if (
        $request->estado === 'Entregado'
        &&
        $request->filled('tipo_pago')
    ) {

        $pedido->tipo_pago =
            $request->tipo_pago;

    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar estado
    |--------------------------------------------------------------------------
    */

    $pedido->estado =
        $request->estado;

    $pedido->save();


    /*
    |--------------------------------------------------------------------------
    | Crear venta cuando el pedido pasa a Entregado
    |--------------------------------------------------------------------------
    */

    if (
        $request->estado === 'Entregado'
        &&
        !Venta::where(
            'pedido_id',
            $pedido->id
        )->exists()
    ) {


        /*
        |--------------------------------------------------------------------------
        | Tipo de pago elegido al entregar
        |--------------------------------------------------------------------------
        */

        $tipoPago =
            $request->tipo_pago;


        /*
        |--------------------------------------------------------------------------
        | Determinar estado del pago
        |--------------------------------------------------------------------------
        */

        if ($tipoPago === 'fiado') {

            $estadoPago = 'pendiente';

            $saldoPendiente =
                $pedido->total;

        } else {

            $estadoPago = 'pagada';

            $saldoPendiente = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Crear venta
        |--------------------------------------------------------------------------
        */

        $venta = Venta::create([

            'user_id' =>
                Auth::id(),

            'cliente_id' =>
                $pedido->cliente_id,

            'pedido_id' =>
                $pedido->id,

            'fecha' =>
                Carbon::now(),

            'total' =>
                $pedido->total,

            'estado' =>
                true,

            'tipo_pago' =>
                $tipoPago,

            'estado_pago' =>
                $estadoPago,

            'saldo_pendiente' =>
                $saldoPendiente,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Crear detalles de la venta
        |--------------------------------------------------------------------------
        */

        foreach (
            $pedido->detalles
            as $detalle
        ) {

            DetalleVenta::create([

                'venta_id' =>
                    $venta->id,

                'producto_id' =>
                    $detalle->producto_id,

                'cantidad' =>
                    $detalle->cantidad,

                'precio' =>
                    $detalle->precio,

                'subtotal' =>
                    $detalle->subtotal,

            ]);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Redireccionar
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('pedidos.index')
        ->with(
            'success',
            'Pedido actualizado y venta registrada correctamente.'
        );

}


}