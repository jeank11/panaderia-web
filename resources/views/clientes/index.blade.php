@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Clientes
</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('clientes.create') }}"
   class="btn btn-success mb-3">
    Nuevo Cliente
</a>

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <form method="GET" action="{{ route('clientes.index') }}">

            <div class="row">

                <div class="col-md-5 mb-3">

                    <label class="form-label">
                        Buscar
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Nombre, documento, teléfono o email..."
                        value="{{ request('buscar') }}">

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Estado
                    </label>

                    <select
                        name="estado"
                        class="form-select">

                        <option value="">Todos</option>

                        <option value="1"
                            @selected(request('estado') === '1')>
                            Activos
                        </option>

                        <option value="0"
                            @selected(request('estado') === '0')>
                            Inactivos
                        </option>

                    </select>

                </div>

                <div class="col-md-2 mb-3">

                    <label class="form-label">
                        Fiado
                    </label>

                    <select
                        name="permite_fiado"
                        class="form-select">

                        <option value="">Todos</option>

                        <option value="1"
                            @selected(request('permite_fiado') === '1')>
                            Sí
                        </option>

                        <option value="0"
                            @selected(request('permite_fiado') === '0')>
                            No
                        </option>

                    </select>

                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end gap-2">

                    <button class="btn btn-primary w-100">
                        Buscar
                    </button>

                    <a href="{{ route('clientes.index') }}"
                       class="btn btn-secondary">
                        Limpiar
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle bg-white">

    <thead class="table-dark text-center">

        <tr>

            <th>Cliente</th>
            <th>Documento</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Fiado</th>
            <th>Límite</th>
            <th>Deuda</th>
            <th>Estado</th>
            <th width="260">Acciones</th>

        </tr>

    </thead>

    <tbody>

@forelse($clientes as $cliente)

<tr>

    <td>
        <strong>{{ $cliente->nombre_completo }}</strong>
    </td>

    <td>
        {{ $cliente->documento }}
    </td>

    <td>
        {{ $cliente->telefono }}
    </td>

    <td>
        {{ $cliente->email }}
    </td>

    <td class="text-center">

        @if($cliente->permite_fiado)

            <span class="badge bg-success">
                Sí
            </span>

        @else

            <span class="badge bg-secondary">
                No
            </span>

        @endif

    </td>

    <td class="text-end">

        ${{ number_format($cliente->limite_credito,2) }}

    </td>

    <td class="text-end">

        @if(($cliente->deuda_actual ?? 0) > 0)

            <span class="fw-bold text-danger">
                ${{ number_format($cliente->deuda_actual,2) }}
            </span>

        @else

            <span class="fw-bold text-success">
                $0.00
            </span>

        @endif

    </td>

    <td class="text-center">

        @if($cliente->estado)

            <span class="badge bg-success">
                Activo
            </span>

        @else

            <span class="badge bg-danger">
                Inactivo
            </span>

        @endif

    </td>

    <td class="text-center">

        <a href="{{ route('clientes.edit',$cliente) }}"
           class="btn btn-warning btn-sm mb-1">
            Editar
        </a>

        <a href="{{ route('clientes.cuenta',$cliente) }}"
           class="btn btn-primary btn-sm mb-1">
            Cuenta
        </a>

        <form action="{{ route('clientes.estado',$cliente) }}"
              method="POST"
              class="d-inline">

            @csrf
            @method('PATCH')

            <button
                type="submit"
                class="btn btn-sm {{ $cliente->estado ? 'btn-danger' : 'btn-success' }}"
                onclick="return confirm('¿Desea cambiar el estado del cliente?')">

                @if($cliente->estado)

                    Desactivar

                @else

                    Activar

                @endif

            </button>

        </form>

    </td>

</tr>

@empty

<tr>

    <td colspan="9" class="text-center py-4">

        No existen clientes registrados.

    </td>

</tr>

@endforelse

    </tbody>

</table>

</div>

<div class="mt-3">

    {{ $clientes->links() }}

</div>

@endsection
