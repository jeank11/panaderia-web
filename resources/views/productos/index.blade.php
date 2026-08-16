@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Productos
</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('productos.create') }}" class="btn btn-success mb-3">
    Nuevo producto
</a>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('productos.index') }}">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Buscar</label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Código o nombre..."
                        value="{{ request('buscar') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Categoría</label>

                    <select name="categoria" class="form-select">

                        <option value="">Todas</option>

                        @foreach($categorias as $categoria)

                            <option
                                value="{{ $categoria->id }}"
                                @selected(request('categoria') == $categoria->id)>

                                {{ $categoria->nombre }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">Estado</label>

                    <select name="estado" class="form-select">

                        <option value="">Todos</option>

                        <option value="1" @selected(request('estado') === '1')>
                            Activos
                        </option>

                        <option value="0" @selected(request('estado') === '0')>
                            Inactivos
                        </option>

                    </select>

                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end gap-2">

                    <button class="btn btn-primary w-100">
                        Buscar
                    </button>

                    <a href="{{ route('productos.index') }}"
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

            <th>Imagen</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio Venta</th>
            <th>Stock</th>
            <th>Estado</th>
            <th width="180">Acciones</th>

        </tr>

    </thead>

    <tbody>

    @forelse($productos as $producto)

        <tr>

            <td class="text-center">

                @if($producto->imagen)

                    <img
                        src="{{ asset('storage/'.$producto->imagen) }}"
                        width="60"
                        height="60"
                        class="rounded shadow-sm"
                        style="object-fit: cover;">

                @else

                    <span class="text-muted">
                        Sin imagen
                    </span>

                @endif

            </td>

            <td class="text-center">

                <span class="badge bg-secondary">
                    {{ $producto->codigo }}
                </span>

            </td>

            <td>
                {{ $producto->nombre }}
            </td>

            <td>
                {{ $producto->categoria->nombre }}
            </td>

            <td class="text-end">

                <span class="fw-bold text-success">
                    ${{ number_format($producto->precio_venta,2) }}
                </span>

            </td>

            <td class="text-center">

                @if($producto->stock == 0)

                    <span class="badge bg-danger">
                        {{ $producto->stock }}
                    </span>

                @elseif($producto->stock <= $producto->stock_minimo)

                    <span class="badge bg-warning text-dark">
                        {{ $producto->stock }}
                    </span>

                @else

                    <span class="badge bg-success">
                        {{ $producto->stock }}
                    </span>

                @endif

            </td>

            <td class="text-center">

                @if($producto->estado)

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

                <a href="{{ route('productos.edit',$producto) }}"
                   class="btn btn-primary btn-sm">
                    Editar
                </a>

                <form action="{{ route('productos.estado',$producto) }}"
                      method="POST"
                      class="d-inline">

                    @csrf
                    @method('PATCH')

                    <button class="btn btn-warning btn-sm">

                        @if($producto->estado)

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

            <td colspan="8" class="text-center py-4">

                No existen productos registrados.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</div>

<div class="mt-3">

    {{ $productos->links() }}

</div>

@endsection