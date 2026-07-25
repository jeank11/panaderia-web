@extends('layouts.cliente')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow">

            <div class="card-header bg-warning">

                <h3 class="mb-0">
                    ✏️ Editar Perfil
                </h3>

            </div>


            <div class="card-body">


                <form method="POST"
                      action="{{ route('clientes.perfil.actualizar') }}">

                    @csrf
                    @method('PUT')


                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nombre
                            </label>


                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="{{ old('nombre',$cliente->nombre) }}"
                                required>

                        </div>



                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Apellido
                            </label>


                            <input
                                type="text"
                                name="apellido"
                                class="form-control"
                                value="{{ old('apellido',$cliente->apellido) }}"
                                required>

                        </div>


                    </div>




                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Teléfono
                            </label>


                            <input
                                type="text"
                                name="telefono"
                                class="form-control"
                                value="{{ old('telefono',$cliente->telefono) }}"
                                required>

                        </div>




                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Fecha de nacimiento
                            </label>


                            <input
                                type="date"
                                name="fecha_nacimiento"
                                class="form-control"
                                value="{{ old('fecha_nacimiento',$cliente->fecha_nacimiento) }}">

                        </div>


                    </div>




                    <div class="mb-3">

                        <label class="form-label">

                            Dirección

                        </label>


                        <input
                            type="text"
                            name="direccion"
                            class="form-control"
                            value="{{ old('direccion',$cliente->direccion) }}"
                            required>

                    </div>




                    <div class="mb-3">

                        <label class="form-label">

                            Email

                        </label>


                        <input
                            type="email"
                            class="form-control"
                            value="{{ $cliente->email }}"
                            readonly>

                    </div>




                    <div class="text-end">


                        <a href="{{ route('clientes.perfil') }}"
                           class="btn btn-secondary">

                            Cancelar

                        </a>



                        <button
                            class="btn btn-success">

                            Guardar cambios

                        </button>


                    </div>



                </form>


            </div>

        </div>

    </div>

</div>


@endsection