<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Pedido;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | VENTAS
        |--------------------------------------------------------------------------
        */

        // Total vendido hoy
        $ventasHoy = Venta::where('estado', true)
            ->whereDate('fecha', today())
            ->sum('total');


        // Ventas contado de hoy
        $ventasContadoHoy = Venta::where('estado', true)
            ->whereDate('fecha', today())
            ->where('tipo_pago', 'contado')
            ->sum('total');


        // Ventas fiadas de hoy
        $ventasFiadoHoy = Venta::where('estado', true)
            ->whereDate('fecha', today())
            ->where('tipo_pago', 'fiado')
            ->sum('total');


        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS
        |--------------------------------------------------------------------------
        */

        $cantidadProductos = Producto::where('estado', true)
            ->count();


        // Productos con stock bajo
        $productosStockBajo = Producto::where('estado', true)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->count();


        // Lista de productos con stock bajo
        $stockBajo = Producto::where('estado', true)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->orderBy('stock')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CLIENTES
        |--------------------------------------------------------------------------
        */

        $cantidadClientes = Cliente::where('estado', true)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | CUENTA CORRIENTE
        |--------------------------------------------------------------------------
        */

        $saldoPendiente = Venta::where('estado', true)
            ->where('tipo_pago', 'fiado')
            ->sum('saldo_pendiente');


        /*
        |--------------------------------------------------------------------------
        | PEDIDOS
        |--------------------------------------------------------------------------
        */

        $pedidosPendientes = Pedido::where(
            'estado',
            'Pendiente'
        )->count();


        $pedidosPreparando = Pedido::where(
            'estado',
            'Preparando'
        )->count();


        $pedidosListos = Pedido::where(
            'estado',
            'Listo'
        )->count();


        $pedidosEntregados = Pedido::where(
            'estado',
            'Entregado'
        )->count();


        $pedidosCancelados = Pedido::where(
            'estado',
            'Cancelado'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMAS VENTAS
        |--------------------------------------------------------------------------
        */

        $ultimasVentas = Venta::with('cliente')
            ->where('estado', true)
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMOS PEDIDOS
        |--------------------------------------------------------------------------
        */

        $ultimosPedidos = Pedido::with('cliente')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(

            'ventasHoy',

            'ventasContadoHoy',

            'ventasFiadoHoy',

            'cantidadProductos',

            'cantidadClientes',

            'productosStockBajo',

            'stockBajo',

            'saldoPendiente',

            'pedidosPendientes',

            'pedidosPreparando',

            'pedidosListos',

            'pedidosEntregados',

            'pedidosCancelados',

            'ultimasVentas',

            'ultimosPedidos'

        ));
    }
}


