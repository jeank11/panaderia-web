
@extends('layouts.cliente')

@section('contenido')

<div class="container py-4">

    {{-- Encabezado --}}
    <div class="text-center mb-4">

        <div
            class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center shadow"
            style="width: 80px; height: 80px; font-size: 38px;">
            ✏️
        </div>

        <h2 class="mt-3 mb-1">
            Editar mi perfil
        </h2>

        <p class="text-muted mb-0">
            Actualizá tus datos personales
        </p>

    </div>


    {{-- Errores de validación --}}
    @if($errors->any())

        <div class="row justify-content-center">

            <div class="col-lg-9">

                <div class="alert alert-danger shadow-sm">

                    <strong>⚠️ Revisá los siguientes datos:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- Formulario --}}
    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card border-0 shadow-sm">

                {{-- Cabecera --}}
                <div class="card-header bg-warning py-3">

                    <div class="d-flex align-items-center">

                        <div class="me-3 fs-3">
                            ✏️
                        </div>

                        <div>

                            <h4 class="mb-0">
                                Información personal
                            </h4>

                            <small>
                                Modificá los datos que quieras actualizar
                            </small>

                        </div>

                    </div>

                </div>


                {{-- Contenido --}}
                <div class="card-body p-4">

                    <form
                        method="POST"
                        action="{{ route('clientes.perfil.actualizar') }}">

                        @csrf
                        @method('PUT')


                        {{-- Nombre y apellido --}}
                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    👤 Nombre

                                </label>

                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $cliente->nombre) }}"
                                    required>

                                @error('nombre')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    👤 Apellido

                                </label>

                                <input
                                    type="text"
                                    name="apellido"
                                    class="form-control @error('apellido') is-invalid @enderror"
                                    value="{{ old('apellido', $cliente->apellido) }}"
                                    required>

                                @error('apellido')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- Teléfono y fecha --}}
                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    📱 Teléfono

                                </label>

                                <input
                                    type="text"
                                    name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $cliente->telefono) }}"
                                    required>

                                @error('telefono')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    🎂 Fecha de nacimiento

                                </label>

                                <input
                                    type="date"
                                    name="fecha_nacimiento"
                                    class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                    value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento) }}">

                                @error('fecha_nacimiento')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- Dirección --}}
                        <div class="mb-4">

                            <label class="form-label">

                                🏠 Dirección

                            </label>

                            <input
                                type="text"
                                name="direccion"
                                class="form-control @error('direccion') is-invalid @enderror"
                                value="{{ old('direccion', $cliente->direccion) }}"
                                required>

                            @error('direccion')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="mb-4">

                            <label class="form-label">

                                📧 Correo electrónico

                            </label>

                            <input
                                type="email"
                                class="form-control bg-light"
                                value="{{ $cliente->email }}"
                                readonly>

                            <small class="text-muted">
                                El correo electrónico no puede modificarse desde aquí.
                            </small>

                        </div>


                        {{-- Botones --}}
                        <div class="border-top pt-4 mt-4">

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                <a
                                    href="{{ route('clientes.perfil') }}"
                                    class="btn btn-outline-secondary">

                                    ↩️ Cancelar

                                </a>


                                <button
                                    type="submit"
                                    class="btn btn-success">

                                    💾 Guardar cambios

                                </button>

                            </div>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

