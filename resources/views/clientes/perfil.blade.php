@extends('layouts.cliente')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h3 class="mb-0">
                    👤 Mi Perfil
                </h3>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Nombre</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->nombre_completo }}"
                            readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Documento</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->documento }}"
                            readonly>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Email</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->email }}"
                            readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Teléfono</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->telefono }}"
                            readonly>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        <strong>Dirección</strong>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $cliente->direccion }}"
                        readonly>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        <strong>Fecha de nacimiento</strong>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}"
                        readonly>

                </div>

            </div>

            <div class="card-footer text-end">

                <a href="{{ route('clientes.perfil.editar') }}"
   class="btn btn-warning">

                    ✏️ Editar Perfil

                </a>

            </div>

        </div>

    </div>

</div>

@endsection