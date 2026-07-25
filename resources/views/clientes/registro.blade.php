@extends('layouts.web')

@section('titulo', 'Registro de cliente')

@section('contenido')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-warning text-center">

                    <h3 class="mb-0">
                        Crear cuenta
                    </h3>

                </div>


                <div class="card-body">


                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                            </ul>

                        </div>

                    @endif



                    <form method="POST"
                          action="{{ route('clientes.registro.store') }}">

                        @csrf


                        <div class="mb-3">

                            <label class="form-label">
                                Nombre
                            </label>

                            <input type="text"
                                   name="nombre"
                                   class="form-control"
                                   value="{{ old('nombre') }}">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Apellido
                            </label>

                            <input type="text"
                                   name="apellido"
                                   class="form-control"
                                   value="{{ old('apellido') }}">

                        </div>

                        <div class="mb-3">

    <label class="form-label">
        Documento
    </label>

    <input type="text"
           name="documento"
           class="form-control"
           value="{{ old('documento') }}">

</div>



                        <div class="mb-3">

                            <label class="form-label">
                                Teléfono
                            </label>

                            <input type="text"
                                   name="telefono"
                                   class="form-control">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Dirección
                            </label>

                            <input type="text"
                                   name="direccion"
                                   class="form-control">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Correo electrónico
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Contraseña
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Confirmar contraseña
                            </label>

                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control">

                        </div>



                        <button class="btn btn-warning w-100">

                            Registrarme

                        </button>


                    </form>


                </div>

            </div>

        </div>

    </div>

</div>

@endsection