@extends('layouts.cliente')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h3 class="mb-0">
                    👤 Mi Perfil
                </h3>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Nombre</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->nombre_completo }}"
                            readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Documento</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->documento }}"
                            readonly>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Email</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->email }}"
                            readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            <strong>Teléfono</strong>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $cliente->telefono }}"
                            readonly>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        <strong>Dirección</strong>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $cliente->direccion }}"
                        readonly>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        <strong>Fecha de nacimiento</strong>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}"
                        readonly>

                </div>

            </div>
            <hr>

<h4 class="mb-3">
    🛒 Mis Compras
</h4>

@if($cliente->ventas->count())

<table class="table table-bordered table-hover">

    <thead class="table-dark">

        <tr>

            <th>Fecha</th>

            <th>Total</th>

            <th>Estado</th>

            <th width="120">Acción</th>

        </tr>

    </thead>

    <tbody>

        @foreach($cliente->ventas as $venta)

        <tr>

            <td>
                {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
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

                <a
                    href="{{ route('ventas.show',$venta) }}"
                    class="btn btn-primary btn-sm">

                    Ver

                </a>

            </td>

        </tr>

        @endforeach

    </tbody>

</table>

@else

<div class="alert alert-info">

    Todavía no tienes compras registradas.

</div>

@endif
            <div class="card-footer text-end">

                <a href="#" class="btn btn-warning">

                    ✏️ Editar Perfil

                </a>

            </div>

        </div>

    </div>

</div>

@endsection