@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Ventas</h2>

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif


<a href="{{ route('ventas.create') }}" class="btn btn-success mb-3">
    Nueva Venta
</a>


<table class="table table-bordered bg-white">

    <thead class="table-dark">

        <tr>

            <th>Fecha</th>
            <th>Cliente</th>
            <th>Usuario</th>
            <th>Total</th>
            <th>Estado</th>
            <th width="120">Acciones</th>

        </tr>

    </thead>


    <tbody>


@forelse($ventas as $venta)


<tr>

    <td>
        {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
    </td>


    <td>
        {{ $venta->cliente->nombre_completo }}
    </td>


    <td>
        {{ $venta->usuario->name }}
    </td>


    <td>
        ${{ number_format($venta->total,2) }}
    </td>


    <td>

        @if($venta->estado)

            <span class="badge bg-success">
                Activa
            </span>

        @else

            <span class="badge bg-danger">
                Anulada
            </span>

        @endif

    </td>


    <td>

        <a href="{{ route('ventas.show',$venta) }}"
           class="btn btn-primary btn-sm">

            Ver

        </a>
        @if($venta->estado)

<form action="{{ route('ventas.anular',$venta) }}"
      method="POST"
      style="display:inline">

    @csrf
    @method('PUT')


    <button
        class="btn btn-danger btn-sm"
        onclick="return confirm('¿Está seguro de anular esta venta?')">

        Anular

    </button>

</form>

@endif

    </td>


</tr>


@empty


<tr>

<td colspan="6" class="text-center">

    No existen ventas registradas.

</td>

</tr>


@endforelse


    </tbody>


</table>


{{ $ventas->links() }}


@endsection