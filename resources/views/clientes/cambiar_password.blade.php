
@extends('layouts.cliente')

@section('contenido')

<div class="container py-4">

    {{-- Encabezado --}}
    <div class="text-center mb-4">

        <div
            class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center shadow"
            style="width: 80px; height: 80px; font-size: 38px;">
            🔐
        </div>

        <h2 class="mt-3 mb-1">
            Cambiar contraseña
        </h2>

        <p class="text-muted mb-0">
            Mantené segura tu cuenta actualizando tu contraseña
        </p>

    </div>


    {{-- Mensaje de error --}}
    @if(session('error'))

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="alert alert-danger shadow-sm">

                    ⚠️ {{ session('error') }}

                </div>

            </div>

        </div>

    @endif


    {{-- Errores de validación --}}
    @if($errors->any())

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="alert alert-danger shadow-sm">

                    <strong>⚠️ Revisá los siguientes datos:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- Formulario --}}
    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">

                {{-- Cabecera --}}
                <div class="card-header bg-warning py-3">

                    <div class="d-flex align-items-center">

                        <div class="me-3 fs-3">
                            🔐
                        </div>

                        <div>

                            <h4 class="mb-0">
                                Seguridad de la cuenta
                            </h4>

                            <small>
                                Ingresá tu contraseña actual y elegí una nueva
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    <form
                        method="POST"
                        action="{{ route('clientes.password.update') }}">

                        @csrf
                        @method('PUT')


                        {{-- Contraseña actual --}}
                        <div class="mb-4">

                            <label class="form-label">

                                🔑 Contraseña actual

                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password_actual"
                                    id="password_actual"
                                    class="form-control @error('password_actual') is-invalid @enderror"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="mostrarPassword('password_actual', this)"
                                    title="Mostrar contraseña">

                                    👁️

                                </button>

                            </div>

                            @error('password_actual')

                                <div class="text-danger small mt-1">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- Nueva contraseña --}}
                        <div class="mb-4">

                            <label class="form-label">

                                🔐 Nueva contraseña

                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="mostrarPassword('password', this)"
                                    title="Mostrar contraseña">

                                    👁️

                                </button>

                            </div>

                            @error('password')

                                <div class="text-danger small mt-1">

                                    {{ $message }}

                                </div>

                            @enderror

                            <div class="form-text">

                                💡 Elegí una contraseña que sea difícil de adivinar.

                            </div>

                        </div>


                        {{-- Confirmar contraseña --}}
                        <div class="mb-4">

                            <label class="form-label">

                                🔐 Confirmar nueva contraseña

                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="mostrarPassword('password_confirmation', this)"
                                    title="Mostrar contraseña">

                                    👁️

                                </button>

                            </div>

                        </div>


                        {{-- Acciones --}}
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

                                    💾 Guardar contraseña

                                </button>

                            </div>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Mostrar / ocultar contraseña --}}
<script>

function mostrarPassword(id, boton)
{
    const input = document.getElementById(id);

    if (input.type === 'password') {

        input.type = 'text';

        boton.innerHTML = '🙈';
        boton.title = 'Ocultar contraseña';

    } else {

        input.type = 'password';

        boton.innerHTML = '👁️';
        boton.title = 'Mostrar contraseña';

    }
}

</script>

@endsection

