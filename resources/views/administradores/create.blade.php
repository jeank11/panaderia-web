@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    ➕ Nuevo Administrador
</h2>

<div class="card">

    <div class="card-header">
        Registrar administrador
    </div>

    <div class="card-body">

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>Hay algunos errores:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('administradores.store') }}"
            method="POST">

            @csrf


            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        <strong>Nombre</strong>
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        required>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        <strong>Email</strong>
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        <strong>Contraseña</strong>
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>

                    <small class="text-muted">
                        Mínimo 6 caracteres.
                    </small>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        <strong>Confirmar contraseña</strong>
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required>

                </div>

            </div>


            <div class="mt-3">

                <button
                    type="submit"
                    class="btn btn-success">

                    💾 Guardar Administrador

                </button>


                <a
                    href="{{ route('administradores.index') }}"
                    class="btn btn-secondary">

                    Cancelar

                </a>

            </div>

        </form>

    </div>

</div>

@endsection