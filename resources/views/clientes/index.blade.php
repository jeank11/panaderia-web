@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Clientes</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('clientes.create') }}" class="btn btn-success mb-3">
    Nuevo Cliente
</a>

<table class="table table-bordered bg-white">

    <thead class="table-dark">

        <tr>

            <th>Nombre</th>
            <th>Documento</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Estado</th>
            <th width="150">Acciones</th>

        </tr>

    </thead>

    <tbody>

@forelse($clientes as $cliente)

<tr>

    <td>{{ $cliente->nombre_completo }}</td>

    <td>{{ $cliente->documento }}</td>

    <td>{{ $cliente->telefono }}</td>

    <td>{{ $cliente->email }}</td>

    <td>
        @if($cliente->estado)
            <span class="badge bg-success">Activo</span>
        @else
            <span class="badge bg-danger">Inactivo</span>
        @endif
    </td>

    <td>

    <a href="{{ route('clientes.edit', $cliente) }}"
       class="btn btn-warning btn-sm mb-1">
        Editar
    </a>

    <form action="{{ route('clientes.estado', $cliente) }}"
          method="POST"
          class="d-inline">

        @csrf
        @method('PATCH')

@if($cliente->estado)

    <button
        type="submit"
        class="btn btn-danger btn-sm"
        onclick="return confirm('¿Desea desactivar este cliente?')">

        Desactivar

    </button>

@else

    <button
        type="submit"
        class="btn btn-success btn-sm">

        Activar

    </button>

@endif

    </form>

</td>

</tr>

@empty

<tr>
    <td colspan="6" class="text-center">
        No existen clientes registrados.
    </td>
</tr>

@endforelse

    </tbody>

</table>

{{ $clientes->links() }}

@endsection