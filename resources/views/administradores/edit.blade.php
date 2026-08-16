@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    ✏️ Editar Administrador
</h2>

<div class="card">

    <div class="card-header">
        Modificar administrador
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
    action="{{ route('administradores.update', $administrador) }}"
    method="POST">

    @csrf
    @method('PUT')


            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        <strong>Nombre</strong>
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $administrador->name) }}"
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
                        value="{{ old('email', $administrador->email) }}"
                        required>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        <strong>Nueva contraseña</strong>
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control">

                    <small class="text-muted">
                        Dejá este campo vacío si no querés cambiar la contraseña.
                    </small>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        <strong>Confirmar nueva contraseña</strong>
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control">

                </div>

            </div>


            <div class="mt-3">

                <button
                    type="submit"
                    class="btn btn-success">

                    💾 Guardar Cambios

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