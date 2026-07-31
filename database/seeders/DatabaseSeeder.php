<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Usuario administrador
        |--------------------------------------------------------------------------
        */
        $this->call([
            AdminUserSeeder::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Categorías
        |--------------------------------------------------------------------------
        */

        $panes = Categoria::firstOrCreate(
            ['nombre' => 'Panes'],
            [
                'descripcion' => 'Panes frescos elaborados diariamente',
                'estado' => true
            ]
        );

        $facturas = Categoria::firstOrCreate(
            ['nombre' => 'Facturas'],
            [
                'descripcion' => 'Facturas y productos de desayuno',
                'estado' => true
            ]
        );

        $tortas = Categoria::firstOrCreate(
            ['nombre' => 'Tortas'],
            [
                'descripcion' => 'Tortas artesanales',
                'estado' => true
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Productos
        |--------------------------------------------------------------------------
        */

        Producto::firstOrCreate(
            ['codigo' => 'PAN001'],
            [
                'nombre' => 'Pan Francés',
                'categoria_id' => $panes->id,
                'precio_compra' => 10,
                'precio_venta' => 20,
                'stock' => 50,
                'stock_minimo' => 10,
                'estado' => true
            ]
        );

        Producto::firstOrCreate(
            ['codigo' => 'PAN002'],
            [
                'nombre' => 'Pan Integral',
                'categoria_id' => $panes->id,
                'precio_compra' => 15,
                'precio_venta' => 30,
                'stock' => 30,
                'stock_minimo' => 5,
                'estado' => true
            ]
        );

        Producto::firstOrCreate(
            ['codigo' => 'FAC001'],
            [
                'nombre' => 'Croissant',
                'categoria_id' => $facturas->id,
                'precio_compra' => 20,
                'precio_venta' => 45,
                'stock' => 40,
                'stock_minimo' => 10,
                'estado' => true
            ]
        );

        Producto::firstOrCreate(
            ['codigo' => 'TOR001'],
            [
                'nombre' => 'Torta de Chocolate',
                'categoria_id' => $tortas->id,
                'precio_compra' => 200,
                'precio_venta' => 400,
                'stock' => 10,
                'stock_minimo' => 2,
                'estado' => true
            ]
        );
    }
}