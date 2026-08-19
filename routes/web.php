<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\ClienteRegistroController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PagoClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\TransferenciaController;


/*
|--------------------------------------------------------------------------
| Página pública
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [WebController::class, 'inicio']
);


/*
|--------------------------------------------------------------------------
| Productos públicos
|--------------------------------------------------------------------------
*/

Route::get(
    '/producto/{producto}',
    [ProductoController::class, 'detalle']
)->name('producto.detalle');


/*
|--------------------------------------------------------------------------
| Carrito
|--------------------------------------------------------------------------
*/

Route::get(
    '/carrito',
    [CarritoController::class, 'index']
)->name('carrito.index');


Route::post(
    '/carrito/agregar/{producto}',
    [CarritoController::class, 'agregar']
)->name('carrito.agregar');


Route::post(
    '/carrito/aumentar/{producto}',
    [CarritoController::class, 'aumentar']
)->name('carrito.aumentar');


Route::post(
    '/carrito/disminuir/{producto}',
    [CarritoController::class, 'disminuir']
)->name('carrito.disminuir');


Route::post(
    '/carrito/eliminar/{producto}',
    [CarritoController::class, 'eliminar']
)->name('carrito.eliminar');


Route::post(
    '/carrito/vaciar',
    [CarritoController::class, 'vaciar']
)->name('carrito.vaciar');


/*
|--------------------------------------------------------------------------
| Pedidos realizados desde el portal del cliente
|--------------------------------------------------------------------------
*/
Route::get(
    '/portal/pago/{pago}',
    [ClienteAuthController::class, 'detallePago']
)->name('clientes.detalle.pago');

Route::get(
    '/pedido/confirmar',
    [PedidoController::class, 'confirmar']
)->name('pedido.confirmar');


Route::post(
    '/pedido/guardar',
    [PedidoController::class, 'guardar']
)->name('pedido.guardar');


/*
|--------------------------------------------------------------------------
| Registro de clientes
|--------------------------------------------------------------------------
*/

Route::get(
    '/clientes/registro',
    [ClienteRegistroController::class, 'create']
)->name('clientes.registro');


Route::post(
    '/clientes/registro',
    [ClienteRegistroController::class, 'store']
)->name('clientes.registro.store');


/*
|--------------------------------------------------------------------------
| Portal del cliente
|--------------------------------------------------------------------------
*/

Route::get(
    '/clientes/transferencia',
    [ClienteAuthController::class, 'transferenciaCreate']
)->name('clientes.transferencia.create');


Route::post(
    '/clientes/transferencia',
    [ClienteAuthController::class, 'transferenciaStore']
)->name('clientes.transferencia.store');



Route::get(
    '/portal/login',
    [ClienteAuthController::class, 'showLogin']
)->name('clientes.login');


Route::post(
    '/portal/login',
    [ClienteAuthController::class, 'login']
)->name('clientes.login.post');


Route::middleware(['cliente'])->group(function () {

    Route::get(
        '/portal/perfil',
        [ClienteAuthController::class, 'perfil']
    )->name('clientes.perfil');


    Route::get(
        '/portal/pedidos',
        [ClienteAuthController::class, 'pedidos']
    )->name('clientes.pedidos');


    Route::get(
        '/portal/pedido/{pedido}',
        [ClienteAuthController::class, 'detallePedido']
    )->name('clientes.pedido.detalle');


    Route::get(
        '/portal/compras',
        [ClienteAuthController::class, 'compras']
    )->name('clientes.compras');


    Route::get(
        '/portal/compra/{venta}',
        [ClienteAuthController::class, 'detalleCompra']
    )->name('clientes.detalle.compra');


    Route::get(
        '/portal/perfil/editar',
        [ClienteAuthController::class, 'editarPerfil']
    )->name('clientes.perfil.editar');


    Route::put(
        '/portal/perfil',
        [ClienteAuthController::class, 'actualizarPerfil']
    )->name('clientes.perfil.actualizar');


    Route::get(
        '/portal/estado-cuenta',
        [ClienteAuthController::class, 'estadoCuenta']
    )->name('clientes.estado_cuenta');


    Route::get(
        '/portal/productos',
        [ClienteAuthController::class, 'productos']
    )->name('clientes.productos');

});


Route::post(
    '/portal/logout',
    [ClienteAuthController::class, 'logout']
)->name('clientes.logout');


Route::get(
    '/portal/cambiar-password',
    [ClienteAuthController::class, 'formPassword']
)->name('clientes.password.form');


Route::put(
    '/portal/cambiar-password',
    [ClienteAuthController::class, 'cambiarPassword']
)->name('clientes.password.update');


/*
|--------------------------------------------------------------------------
| Pagos del cliente
|--------------------------------------------------------------------------
*/

Route::post(
    '/clientes/{cliente}/pago-global',
    [PagoClienteController::class, 'pagoGlobal']
)->name('clientes.pago.global');

Route::get(
    '/pagos/{pago}/detalle',
    [PagoClienteController::class, 'detalle']
)->name('pagos.detalle');


/*
|--------------------------------------------------------------------------
| PANEL ADMINISTRATIVO
| Requiere autenticación
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->name('dashboard');

     /*
|--------------------------------------------------------------------------
| Administradores
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])->group(function () {

    Route::resource(
        'administradores',
        AdministradorController::class
    )->parameters([
        'administradores' => 'administrador'
    ]);

});

    /*
    |--------------------------------------------------------------------------
    | Pedidos
    |--------------------------------------------------------------------------
    */
    Route::get(
    'pedidos/produccion',
    [PedidoController::class, 'produccion']
)->name('pedidos.produccion');
    Route::get(
    'pedidos/produccion/imprimir',
    [PedidoController::class, 'imprimirProduccion']
)->name('pedidos.produccion.imprimir');
    Route::resource(
        'pedidos',
        PedidoController::class
    );


    Route::patch(
        'pedidos/{pedido}/estado',
        [PedidoController::class, 'cambiarEstado']
    )->name('pedidos.estado');


    /*
    |--------------------------------------------------------------------------
    | Ventas
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'ventas',
        VentaController::class
    );


    Route::get(
        'ventas/{venta}/detalle',
        [VentaController::class, 'detalle']
    )->name('ventas.detalle');

 


    Route::get(
        'ventas/{venta}/ticket',
        [VentaController::class, 'ticket']
    )->name('ventas.ticket');


    Route::put(
        'ventas/{venta}/anular',
        [VentaController::class, 'anular']
    )->name('ventas.anular');

    Route::get(
    '/transferencias',
    [TransferenciaController::class, 'index']
    )->name('transferencias.index');

    Route::post(
    '/transferencias/{transferencia}/aprobar',
    [TransferenciaController::class, 'aprobar']
    )->name('transferencias.aprobar');

    Route::post(
    '/transferencias/{transferencia}/rechazar',
    [TransferenciaController::class, 'rechazar']
)->name('transferencias.rechazar');


    /*
    |--------------------------------------------------------------------------
    | Categorías
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'categorias',
        CategoriaController::class
    );


    Route::patch(
        'categorias/{categoria}/estado',
        [CategoriaController::class, 'cambiarEstado']
    )->name('categorias.estado');


    /*
    |--------------------------------------------------------------------------
    | Productos
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'productos',
        ProductoController::class
    );


    Route::patch(
        'productos/{producto}/estado',
        [ProductoController::class, 'cambiarEstado']
    )->name('productos.estado');


    /*
    |--------------------------------------------------------------------------
    | Clientes
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'clientes',
        ClienteController::class
    );


    Route::patch(
        'clientes/{cliente}/estado',
        [ClienteController::class, 'cambiarEstado']
    )->name('clientes.estado');


    Route::get(
        '/clientes/{cliente}/recibo-pago',
        [ClienteController::class, 'reciboPago']
    )->name('clientes.recibo_pago');


    Route::get(
        '/clientes/{cliente}/cuenta',
        [ClienteController::class, 'cuenta']
    )->name('clientes.cuenta');


    /*
    |--------------------------------------------------------------------------
    | Pagos de clientes
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/clientes/{cliente}/pago',
        [PagoClienteController::class, 'store']
    )->name('clientes.pago.store');


    Route::post(
        '/clientes/{cliente}/venta/{venta}/cancelar',
        [PagoClienteController::class, 'cancelar']
    )->name('clientes.pago.cancelar');


    /*
    |--------------------------------------------------------------------------
    | Perfil administrador
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Autenticación Laravel
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';