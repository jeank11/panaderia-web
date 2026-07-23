@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Categorías</h2>

<a href="{{ route('categorias.create') }}" class="btn btn-success mb-3">
    Nueva categoría
</a>

<table class="table table-bordered bg-white">

    <thead class="table-dark">

        <tr>

            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>

        </tr>

    </thead>

    <tbody>

        @forelse($categorias as $categoria)

        <tr>

            <td>{{ $categoria->id }}</td>
            <td>{{ $categoria->nombre }}</td>
            <td>{{ $categoria->descripcion }}</td>

            <td>

                @if($categoria->estado)
                    Activa
                @else
                    Inactiva
                @endif

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="4" class="text-center">
                No existen categorías.
            </td>

        </tr>

        @endforelse

    </tbody>

</table>

{{ $categorias->links() }}

@endsection