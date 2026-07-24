@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Editar Cliente</h2>

<div class="card">

    <div class="card-body">

        <form action="{{ route('clientes.update', $cliente) }}" method="POST">

           @csrf
           @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" value="{{ $cliente->nombre }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" value="{{ $cliente->apellido }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" name="documento" value="{{ $cliente->documento }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" value="{{ $cliente->telefono }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ $cliente->email }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="{{ $cliente->fecha_nacimiento }}" class="form-control">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label" >Dirección</label>
                    <textarea
    name="direccion"
    rows="3"
    class="form-control">{{ $cliente->direccion }}</textarea>
                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">Estado</label>

                    <select name="estado" class="form-select">

                     <option value="1" {{ $cliente->estado ? 'selected' : '' }}>
    Activo
</option>

<option value="0" {{ !$cliente->estado ? 'selected' : '' }}>
    Inactivo
</option>

                    </select>

                </div>

            </div>

            <button class="btn btn-success">
                Guardar Cliente
            </button>

            <a href="{{ route('clientes.index') }}"
               class="btn btn-secondary">

                Cancelar

            </a>

        </form>

    </div>

</div>

@endsection