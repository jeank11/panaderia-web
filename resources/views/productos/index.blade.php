@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Productos</h2>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('productos.create') }}" class="btn btn-success mb-3">
    Nuevo producto
</a>

<table class="table table-bordered bg-white">

    <thead class="table-dark">

    <tr>

        <th>Código</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Precio Venta</th>
        <th>Stock</th>

    </tr>

    </thead>

    <tbody>

    @forelse($productos as $producto)

        <tr>

            <td>{{ $producto->codigo }}</td>

            <td>{{ $producto->nombre }}</td>

            <td>{{ $producto->categoria->nombre }}</td>

            <td>${{ number_format($producto->precio_venta,2) }}</td>

            <td>{{ $producto->stock }}</td>

        </tr>

    @empty

        <tr>

            <td colspan="5" class="text-center">

                No existen productos registrados.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

{{ $productos->links() }}

@endsection