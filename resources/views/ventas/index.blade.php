@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Ventas</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('ventas.create') }}" class="btn btn-success mb-3">
    Nueva venta
</a>

<table class="table table-bordered bg-white">

    <thead class="table-dark">

        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Total</th>
            <th>Estado</th>
        </tr>

    </thead>

    <tbody>

        <tr>

            <td colspan="5" class="text-center">
                No existen ventas registradas.
            </td>

        </tr>

    </tbody>

</table>

@endsection