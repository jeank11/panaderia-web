@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Nuevo Cliente</h2>

<div class="card">

    <div class="card-body">

        <form action="{{ route('clientes.store') }}" method="POST">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" name="documento" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Dirección</label>
                    <textarea
                        name="direccion"
                        rows="3"
                        class="form-control"></textarea>
                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">Estado</label>

                    <select name="estado" class="form-select">

                        <option value="1">Activo</option>

                        <option value="0">Inactivo</option>

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