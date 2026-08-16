@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Categorías
</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('categorias.create') }}"
   class="btn btn-success mb-3">
    Nueva categoría
</a>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('categorias.index') }}">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Buscar
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Nombre o descripción..."
                        value="{{ request('buscar') }}">

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Estado
                    </label>

                    <select
                        name="estado"
                        class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option value="1"
                            @selected(request('estado') === '1')>
                            Activas
                        </option>

                        <option value="0"
                            @selected(request('estado') === '0')>
                            Inactivas
                        </option>

                    </select>

                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end gap-2">

                    <button class="btn btn-primary w-100">
                        Buscar
                    </button>

                    <a href="{{ route('categorias.index') }}"
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

                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Productos</th>
                <th>Estado</th>
                <th width="180">Acciones</th>

            </tr>

        </thead>

        <tbody>

        @forelse($categorias as $categoria)

            <tr>

                <td class="text-center">
                    {{ $categoria->id }}
                </td>

                <td>
                    {{ $categoria->nombre }}
                </td>

                <td>
                    {{ $categoria->descripcion }}
                </td>

                <td class="text-center">

                    @if($categoria->productos_count > 0)
                        <span class="badge bg-primary">
                            {{ $categoria->productos_count }}
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            0
                        </span>
                    @endif

                </td>

                <td class="text-center">

                    @if($categoria->estado)

                        <span class="badge bg-success">
                            Activa
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Inactiva
                        </span>

                    @endif

                </td>

                <td class="text-center">

                    <a href="{{ route('categorias.edit', $categoria) }}"
                       class="btn btn-primary btn-sm">
                        Editar
                    </a>

                    <form action="{{ route('categorias.estado', $categoria) }}"
                          method="POST"
                          class="d-inline">

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn btn-sm {{ $categoria->estado ? 'btn-warning' : 'btn-success' }}">

                            @if($categoria->estado)
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

                <td colspan="6" class="text-center py-4">
                    No existen categorías.
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<div class="mt-3">

    {{ $categorias->links() }}

</div>

@endsection