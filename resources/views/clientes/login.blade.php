@extends('layouts.web')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-md-5">

        <div class="card">

            <div class="card-header text-center">

                <h3>Ingreso de Clientes</h3>

            </div>

            <div class="card-body">

                @if(session('error'))

                    <div class="alert alert-danger">

                        {{ session('error') }}

                    </div>

                @endif

                <form method="POST" action="{{ route('clientes.login.post') }}">

                    @csrf

                    <div class="mb-3">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Contraseña</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>

                    </div>

                    <button
                        class="btn btn-primary w-100">

                        Ingresar

                    </button>
                    <div class="text-center mt-3">

    <p>
        ¿Todavía no tienes una cuenta?
    </p>

    <a href="{{ route('clientes.registro') }}"
       class="btn btn-warning w-100">

        Crear cuenta

    </a>

</div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection