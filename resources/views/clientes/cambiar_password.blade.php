@extends('layouts.cliente')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-md-6">


        <div class="card shadow">


            <div class="card-header bg-warning">

                <h3 class="mb-0">

                    🔐 Cambiar Contraseña

                </h3>

            </div>



            <div class="card-body">


                @if(session('error'))

                    <div class="alert alert-danger">

                        {{ session('error') }}

                    </div>

                @endif



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
                      action="{{ route('clientes.password.update') }}">


                    @csrf

                    @method('PUT')



                    <div class="mb-3">


                        <label class="form-label">

                            Contraseña actual

                        </label>


                        <input

                            type="password"

                            name="password_actual"

                            class="form-control"

                            required>


                    </div>




                    <div class="mb-3">


                        <label class="form-label">

                            Nueva contraseña

                        </label>


                        <input

                            type="password"

                            name="password"

                            class="form-control"

                            required>


                    </div>




                    <div class="mb-3">


                        <label class="form-label">

                            Confirmar nueva contraseña

                        </label>


                        <input

                            type="password"

                            name="password_confirmation"

                            class="form-control"

                            required>


                    </div>




                    <div class="text-end">


                        <a href="{{ route('clientes.perfil') }}"

                           class="btn btn-secondary">

                            Cancelar

                        </a>



                        <button

                            class="btn btn-success">

                            Guardar contraseña

                        </button>


                    </div>



                </form>



            </div>


        </div>


    </div>


</div>


@endsection