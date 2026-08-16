@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Editar Categoría
</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">

    <div class="card-body">

        <form action="{{ route('categorias.update', $categoria) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre', $categoria->nombre) }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    class="form-control"
                    rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>

            </div>

            <div class="d-flex justify-content-between">

                <a href="{{ route('categorias.index') }}"
                   class="btn btn-secondary">
                    Volver
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

@endsection