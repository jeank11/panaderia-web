
@extends('layouts.cliente')

@section('contenido')

<div class="container py-4">

    {{-- Encabezado --}}
    <div class="text-center mb-4">

        <div
            class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center shadow"
            style="width: 80px; height: 80px; font-size: 38px;">
            👤
        </div>

        <h2 class="mt-3 mb-1">
            Mi Perfil
        </h2>

        <p class="text-muted mb-0">
            Consultá y administrá tus datos personales
        </p>

    </div>


    {{-- Tarjeta principal --}}
    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card border-0 shadow-sm">

                {{-- Cabecera --}}
                <div class="card-header bg-primary text-white py-3">

                    <div class="d-flex align-items-center">

                        <div class="me-3 fs-3">
                            👤
                        </div>

                        <div>

                            <h4 class="mb-0">
                                Información personal
                            </h4>

                            <small>
                                Datos registrados en tu cuenta
                            </small>

                        </div>

                    </div>

                </div>


                {{-- Contenido --}}
                <div class="card-body p-4">


                    {{-- Nombre --}}
                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <label class="form-label text-muted">
                                👤 Nombre completo
                            </label>

                            <div class="form-control bg-light">

                                {{ $cliente->nombre_completo }}

                            </div>

                        </div>


                        {{-- Documento --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label text-muted">
                                🪪 Documento
                            </label>

                            <div class="form-control bg-light">

                                {{ $cliente->documento }}

                            </div>

                        </div>

                    </div>


                    {{-- Email y teléfono --}}
                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <label class="form-label text-muted">
                                📧 Correo electrónico
                            </label>

                            <div class="form-control bg-light">

                                {{ $cliente->email }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-4">

                            <label class="form-label text-muted">
                                📱 Teléfono
                            </label>

                            <div class="form-control bg-light">

                                {{ $cliente->telefono ?: 'No registrado' }}

                            </div>

                        </div>

                    </div>


                    {{-- Dirección --}}
                    <div class="mb-4">

                        <label class="form-label text-muted">
                            🏠 Dirección
                        </label>

                        <div class="form-control bg-light">

                            {{ $cliente->direccion ?: 'No registrada' }}

                        </div>

                    </div>


                    {{-- Fecha de nacimiento --}}
                    <div class="mb-2">

                        <label class="form-label text-muted">
                            🎂 Fecha de nacimiento
                        </label>

                        <div class="form-control bg-light">

                            @if($cliente->fecha_nacimiento)

                                {{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}

                            @else

                                No registrada

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Botones --}}
                <div class="card-footer bg-white border-0 p-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <a href="{{ route('clientes.password.form') }}" 
                           class="btn btn-outline-secondary"> 
                           🔑 Cambiar contraseña 
                        </a>


                        <a
                            href="{{ route('clientes.perfil.editar') }}"
                            class="btn btn-warning">

                            ✏️ Editar perfil

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

