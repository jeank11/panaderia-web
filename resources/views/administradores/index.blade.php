@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    👤 Administradores
</h2>

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif

@if(session('error'))

<div class="alert alert-danger">
    {{ session('error') }}
</div>

@endif


<div class="d-flex justify-content-between align-items-center mb-3">

    <h5 class="mb-0">
        Administradores del sistema
    </h5>

    <a href="{{ route('administradores.create') }}"
       class="btn btn-success">

        ➕ Nuevo Administrador

    </a>

</div>


<div class="card shadow-sm">

    <div class="card-body">

        <table class="table table-bordered table-hover bg-white">

            <thead class="table-dark">

                <tr>

                    <th>Nombre</th>

                    <th>Email</th>

                    <th>Rol</th>

                    <th>Estado</th>

                    <th width="180">Acciones</th>

                </tr>

            </thead>


            <tbody>

                @forelse($administradores as $administrador)

                    <tr>

                        <td>
                            {{ $administrador->name }}
                        </td>

                        <td>
                            {{ $administrador->email }}
                        </td>

                        <td>

                            <span class="badge bg-primary">

                                {{ ucfirst($administrador->role) }}

                            </span>

                        </td>

                        <td>

                            @if($administrador->estado)

                                <span class="badge bg-success">
                                    Activo
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactivo
                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('administradores.edit', $administrador) }}"
                               class="btn btn-primary btn-sm">

                                Editar

                            </a>


                            <form
                                action="{{ route('administradores.destroy', $administrador) }}"
                                method="POST"
                                style="display:inline">

                                @csrf
                                @method('DELETE')

                                @if($administrador->estado)

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Está seguro de desactivar este administrador?')">

                                        Desactivar

                                    </button>

                                @else

                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('¿Desea activar este administrador?')">

                                        Activar

                                    </button>

                                @endif

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="text-center">

                            No existen administradores registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        {{ $administradores->links() }}

    </div>

</div>

@endsection