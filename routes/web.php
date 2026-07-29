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



/*
|--------------------------------------------------------------------------
| Página pública
|--------------------------------------------------------------------------
*/

Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])
    ->name('carrito.agregar');
    Route::post('/carrito/aumentar/{producto}', [CarritoController::class, 'aumentar'])
    ->name('carrito.aumentar');

Route::post('/carrito/disminuir/{producto}', [CarritoController::class, 'disminuir'])
    ->name('carrito.disminuir');

Route::post('/carrito/eliminar/{producto}', [CarritoController::class, 'eliminar'])
    ->name('carrito.eliminar');

Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])
    ->name('carrito.vaciar');

Route::get('/carrito', [CarritoController::class, 'index'])
    ->name('carrito.index');

Route::get('/', [WebController::class,'inicio']);


Route::get(
    '/producto/{producto}',
    [ProductoController::class,'detalle']
)->name('producto.detalle');

Route::get(
    '/pedido/confirmar',
    [PedidoController::class,'confirmar']
)
->name('pedido.confirmar');


Route::post(
    '/pedido/guardar',
    [PedidoController::class,'guardar']
)
->name('pedido.guardar');

Route::get(
    '/pedidos/{pedido}',
    [PedidoController::class,'show']
)
->name('pedidos.show');



/*
|--------------------------------------------------------------------------
| Registro de clientes
|--------------------------------------------------------------------------
*/


Route::get(
    '/clientes/registro',
    [ClienteRegistroController::class,'create']
)->name('clientes.registro');


Route::post(
    '/clientes/registro',
    [ClienteRegistroController::class,'store']
)->name('clientes.registro.store');



/*
|--------------------------------------------------------------------------
| Portal del cliente
|--------------------------------------------------------------------------
*/


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
    '/portal/pedido/{pedido}',
    [ClienteAuthController::class, 'detallePedido']
)->name('clientes.pedido.detalle');


    Route::get(
        '/portal/perfil',
        [ClienteAuthController::class, 'perfil']
    )->name('clientes.perfil');

    Route::get(
    '/portal/pedidos',
    [ClienteAuthController::class,'pedidos']
)->name('clientes.pedidos');


Route::get(
    '/portal/compras',
    [ClienteAuthController::class,'compras']
)->name('clientes.compras');

Route::get(
    '/portal/compra/{venta}',
    [ClienteAuthController::class,'detalleCompra']
)->name('clientes.detalle.compra');

Route::get(
    '/portal/perfil/editar',
    [ClienteAuthController::class,'editarPerfil']
)->name('clientes.perfil.editar');


Route::put(
    '/portal/perfil',
    [ClienteAuthController::class,'actualizarPerfil']
)->name('clientes.perfil.actualizar');


});

Route::post(
    '/portal/logout',
    [ClienteAuthController::class,'logout']
)->name('clientes.logout');

Route::get(
    '/portal/cambiar-password',
    [ClienteAuthController::class,'formPassword']
)->name('clientes.password.form');


Route::put(
    '/portal/cambiar-password',
    [ClienteAuthController::class,'cambiarPassword']
)->name('clientes.password.update');

Route::get('/carrito', [CarritoController::class, 'index'])
    ->name('carrito.index');

Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])
    ->name('carrito.agregar');

Route::post('/carrito/eliminar/{producto}', [CarritoController::class, 'eliminar'])
    ->name('carrito.eliminar');

Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])
    ->name('carrito.vaciar');



/*
|--------------------------------------------------------------------------
| Panel administrativo
| Requiere login administrador
|--------------------------------------------------------------------------
*/


Route::middleware(['auth'])->group(function () {

    Route::resource('pedidos', PedidoController::class);
    Route::resource('ventas', VentaController::class);
    Route::resource(
        'categorias',
        CategoriaController::class
    );


    Route::resource(
        'productos',
        ProductoController::class
    );


    Route::resource(
        'ventas',
        VentaController::class
    );


    Route::resource(
        'clientes',
        ClienteController::class
    );



    Route::patch(
        'productos/{producto}/estado',
        [ProductoController::class,'cambiarEstado']
    )->name('productos.estado');



    Route::patch(
        'clientes/{cliente}/estado',
        [ClienteController::class,'cambiarEstado']
    )->name('clientes.estado');



    Route::get(
        'ventas/{venta}/ticket',
        [VentaController::class,'ticket']
    )->name('ventas.ticket');



    Route::put(
        'ventas/{venta}/anular',
        [VentaController::class,'anular']
    )->name('ventas.anular');



    Route::get(
        '/dashboard',
        function () {
            return view('dashboard');
        }
    )->name('dashboard');



    Route::get(
        '/profile',
        [ProfileController::class,'edit']
    )->name('profile.edit');



    Route::patch(
        '/profile',
        [ProfileController::class,'update']
    )->name('profile.update');



    Route::delete(
        '/profile',
        [ProfileController::class,'destroy']
    )->name('profile.destroy');

    Route::patch(
    'pedidos/{pedido}/estado',
    [PedidoController::class, 'cambiarEstado']
)->name('pedidos.estado');

// Rutas administrador

Route::middleware(['auth'])->group(function () {

    Route::get('/clientes/{cliente}/cuenta',
        [ClienteController::class, 'cuenta'])
        ->name('clientes.cuenta');

});


});



/*
|--------------------------------------------------------------------------
| Autenticación Laravel
|--------------------------------------------------------------------------
*/


require __DIR__.'/auth.php';