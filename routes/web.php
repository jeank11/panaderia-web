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


/*
|--------------------------------------------------------------------------
| Página pública
|--------------------------------------------------------------------------
*/


Route::get('/', [WebController::class,'inicio']);


Route::get(
    '/producto/{producto}',
    [ProductoController::class,'detalle']
)->name('producto.detalle');



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


Route::get(
    '/portal/perfil',
    [ClienteAuthController::class, 'perfil']
)->name('clientes.perfil');

Route::get(
    '/portal/logout',
    [ClienteAuthController::class,'logout']
)->name('clientes.logout');



/*
|--------------------------------------------------------------------------
| Panel administrativo
| Requiere login administrador
|--------------------------------------------------------------------------
*/


Route::middleware(['auth'])->group(function () {


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


});



/*
|--------------------------------------------------------------------------
| Autenticación Laravel
|--------------------------------------------------------------------------
*/


require __DIR__.'/auth.php';