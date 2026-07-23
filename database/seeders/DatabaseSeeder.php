<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario demo
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@panaderia.com',
            'password' => Hash::make('12345678'),
        ]);

        // Categorías
        $panes = Categoria::create([
            'nombre' => 'Panes',
            'descripcion' => 'Panes frescos elaborados diariamente',
            'estado' => 1
        ]);

        $facturas = Categoria::create([
            'nombre' => 'Facturas',
            'descripcion' => 'Facturas y productos de desayuno',
            'estado' => 1
        ]);

        $tortas = Categoria::create([
            'nombre' => 'Tortas',
            'descripcion' => 'Tortas artesanales',
            'estado' => 1
        ]);

        // Productos
        Producto::create([
            'codigo' => 'PAN001',
            'nombre' => 'Pan Francés',
            'categoria_id' => $panes->id,
            'precio_compra' => 10,
            'precio_venta' => 20,
            'stock' => 50,
            'stock_minimo' => 10,
            'estado' => 1
        ]);

        Producto::create([
            'codigo' => 'PAN002',
            'nombre' => 'Pan Integral',
            'categoria_id' => $panes->id,
            'precio_compra' => 15,
            'precio_venta' => 30,
            'stock' => 30,
            'stock_minimo' => 5,
            'estado' => 1
        ]);

        Producto::create([
            'codigo' => 'FAC001',
            'nombre' => 'Croissant',
            'categoria_id' => $facturas->id,
            'precio_compra' => 20,
            'precio_venta' => 45,
            'stock' => 40,
            'stock_minimo' => 10,
            'estado' => 1
        ]);

        Producto::create([
            'codigo' => 'TOR001',
            'nombre' => 'Torta de Chocolate',
            'categoria_id' => $tortas->id,
            'precio_compra' => 200,
            'precio_venta' => 400,
            'stock' => 10,
            'stock_minimo' => 2,
            'estado' => 1
        ]);
    }
}
