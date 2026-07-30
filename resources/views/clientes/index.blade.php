```blade
@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Clientes</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<a href="{{ route('clientes.create') }}" class="btn btn-success mb-3">
    Nuevo Cliente
</a>


<table class="table table-bordered bg-white">

    <thead class="table-dark">

        <tr>

            <th>Nombre</th>
            <th>Documento</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Fiado</th>
            <th>Límite crédito</th>
            <th>Deuda actual</th>
            <th>Estado</th>
            <th width="250">Acciones</th>

        </tr>

    </thead>


    <tbody>


@forelse($clientes as $cliente)


<tr>

    <td>
        {{ $cliente->nombre_completo }}
    </td>


    <td>
        {{ $cliente->documento }}
    </td>


    <td>
        {{ $cliente->telefono }}
    </td>


    <td>
        {{ $cliente->email }}
    </td>


    <td>

        @if($cliente->permite_fiado)

            <span class="badge bg-success">
                Sí
            </span>

        @else

            <span class="badge bg-secondary">
                No
            </span>

        @endif

    </td>


    <td>
        ${{ number_format($cliente->limite_credito,2) }}
    </td>


    <td>

        @if(($cliente->deuda_actual ?? 0) > 0)

            <span class="text-danger fw-bold">
                ${{ number_format($cliente->deuda_actual,2) }}
            </span>

        @else

            <span class="text-success fw-bold">
                $0.00
            </span>

        @endif

    </td>


    <td>

        @if($cliente->estado)

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


        <a href="{{ route('clientes.edit', $cliente) }}"
           class="btn btn-warning btn-sm mb-1">

            Editar

        </a>


        <a href="{{ route('clientes.cuenta', $cliente) }}"
           class="btn btn-primary btn-sm mb-1">

            Cuenta corriente

        </a>



        <form action="{{ route('clientes.estado', $cliente) }}"
              method="POST"
              class="d-inline">

            @csrf
            @method('PATCH')


            @if($cliente->estado)

                <button
                    type="submit"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('¿Desea desactivar este cliente?')">

                    Desactivar

                </button>


            @else


                <button
                    type="submit"
                    class="btn btn-success btn-sm">

                    Activar

                </button>


            @endif


        </form>


    </td>


</tr>



@empty


<tr>

    <td colspan="9" class="text-center">

        No existen clientes registrados.

    </td>

</tr>


@endforelse


    </tbody>


</table>


{{ $clientes->links() }}


@endsection
```
