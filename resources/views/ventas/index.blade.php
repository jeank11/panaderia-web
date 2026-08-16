@extends('layouts.app')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">💰 Ventas</h2>

        <p class="text-muted mb-0">
            Registro y control de las ventas realizadas
        </p>
    </div>

    <a href="{{ route('ventas.create') }}" class="btn btn-success">
        ➕ Nueva Venta
    </a>

</div>


@if(session('success'))

<div class="alert alert-success alert-dismissible fade show" role="alert">

    {{ session('success') }}

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

@endif

{{-- FILTROS --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-light">

        <strong>
            🔎 Buscar y filtrar ventas
        </strong>

    </div>


    <div class="card-body">

        <form action="{{ route('ventas.index') }}"
              method="GET">

            <div class="row">


                {{-- BUSCAR CLIENTE --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Cliente / Documento
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Nombre, apellido o documento"
                        value="{{ request('buscar') }}"
                    >

                </div>


                {{-- TIPO DE PAGO --}}

                <div class="col-md-2 mb-3">

                    <label class="form-label">
                        Tipo de pago
                    </label>

                    <select
                        name="tipo_pago"
                        class="form-select"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="contado"
                            {{ request('tipo_pago') == 'contado' ? 'selected' : '' }}
                        >
                            Contado
                        </option>

                        <option
                            value="fiado"
                            {{ request('tipo_pago') == 'fiado' ? 'selected' : '' }}
                        >
                            Fiado
                        </option>

                    </select>

                </div>


                {{-- ESTADO DE PAGO --}}

                <div class="col-md-2 mb-3">

                    <label class="form-label">
                        Estado de pago
                    </label>

                    <select
                        name="estado_pago"
                        class="form-select"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="pagada"
                            {{ request('estado_pago') == 'pagada' ? 'selected' : '' }}
                        >
                            Pagada
                        </option>

                        <option
                            value="pendiente"
                            {{ request('estado_pago') == 'pendiente' ? 'selected' : '' }}
                        >
                            Pendiente
                        </option>

                        <option
                            value="parcial"
                            {{ request('estado_pago') == 'parcial' ? 'selected' : '' }}
                        >
                            Parcial
                        </option>

                    </select>

                </div>


                {{-- FECHA DESDE --}}

                <div class="col-md-2 mb-3">

                    <label class="form-label">
                        Desde
                    </label>

                    <input
                        type="date"
                        name="fecha_desde"
                        class="form-control"
                        value="{{ request('fecha_desde') }}"
                    >

                </div>


                {{-- FECHA HASTA --}}

                <div class="col-md-2 mb-3">

                    <label class="form-label">
                        Hasta
                    </label>

                    <input
                        type="date"
                        name="fecha_hasta"
                        class="form-control"
                        value="{{ request('fecha_hasta') }}"
                    >

                </div>

            </div>


            {{-- BOTONES --}}

            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    🔎 Filtrar

                </button>


                <a
                    href="{{ route('ventas.index') }}"
                    class="btn btn-secondary"
                >

                    🔄 Limpiar

                </a>

            </div>

        </form>

    </div>

</div>


{{-- RESUMEN DE VENTAS --}}

@php

    $totalVentas = $ventas->total();

@endphp


<div class="row mb-4">


    {{-- TOTAL VENTAS --}}

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="text-muted small">
                    Ventas registradas
                </div>

                <div class="fs-3 fw-bold">
                    {{ $totalVentas }}
                </div>

            </div>

        </div>

    </div>


    {{-- CONTADO --}}

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="text-muted small">
                    Ventas al contado
                </div>

                <div class="fs-4 fw-bold text-success">

                    ${{ number_format($totalContado, 2, ',', '.') }}

                </div>

            </div>

        </div>

    </div>


    {{-- FIADO --}}

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="text-muted small">
                    Ventas fiadas
                </div>

                <div class="fs-4 fw-bold text-warning">

                    ${{ number_format($totalFiado, 2, ',', '.') }}

                </div>

            </div>

        </div>

    </div>


    {{-- DEUDA --}}

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="text-muted small">
                    Saldo pendiente
                </div>

                <div class="fs-4 fw-bold text-danger">

                    ${{ number_format($totalPendiente, 2, ',', '.') }}

                </div>

            </div>

        </div>

    </div>

</div>


{{-- TABLA DE VENTAS --}}

<div class="card shadow-sm border-0">

    <div class="card-header bg-dark text-white">

        <strong>
            📋 Registro de ventas
        </strong>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Fecha</th>

                        <th>Cliente</th>

                        <th>Usuario</th>

                        <th>Total</th>

                        <th>Tipo de pago</th>

                        <th>Estado de pago</th>

                        <th>Saldo pendiente</th>

                        <th>Estado</th>

                        <th width="180">Acciones</th>

                    </tr>

                </thead>


                <tbody>


                @forelse($ventas as $venta)


                    <tr>


                        {{-- FECHA --}}

                        <td>

                            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}

                        </td>


                        {{-- CLIENTE --}}

                        <td>

                            @if($venta->cliente)

                                {{ $venta->cliente->nombre_completo }}

                            @else

                                <span class="text-muted">
                                    Consumidor final
                                </span>

                            @endif

                        </td>


                        {{-- USUARIO --}}

                        <td>

                            {{ $venta->usuario->name ?? '—' }}

                        </td>


                        {{-- TOTAL --}}

                        <td>

                            <strong>

                                ${{ number_format($venta->total, 2, ',', '.') }}

                            </strong>

                        </td>


                        {{-- TIPO DE PAGO --}}

                        <td>

                            @if($venta->tipo_pago === 'fiado')

                                <span class="badge bg-warning text-dark">

                                    🟠 Fiado

                                </span>

                            @else

                                <span class="badge bg-success">

                                    🟢 Contado

                                </span>

                            @endif

                        </td>


                        {{-- ESTADO DE PAGO --}}

                        <td>

                            @if($venta->tipo_pago === 'contado')

                                <span class="badge bg-success">

                                    Pagada

                                </span>

                            @elseif($venta->estado_pago === 'pagada')

                                <span class="badge bg-success">

                                    Pagada

                                </span>

                            @elseif($venta->estado_pago === 'parcial')

                                <span class="badge bg-warning text-dark">

                                    Pago parcial

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Pendiente

                                </span>

                            @endif

                        </td>


                        {{-- SALDO PENDIENTE --}}

                        <td>

                            @if($venta->tipo_pago === 'fiado' && $venta->saldo_pendiente > 0)

                                <strong class="text-danger">

                                    ${{ number_format($venta->saldo_pendiente, 2, ',', '.') }}

                                </strong>

                            @else

                                <span class="text-muted">

                                    $0,00

                                </span>

                            @endif

                        </td>


                        {{-- ESTADO DE LA VENTA --}}

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


                        {{-- ACCIONES --}}

                        <td>

                            <div class="d-flex gap-1">


                                <a href="{{ route('ventas.show', $venta) }}"
                                   class="btn btn-primary btn-sm">

                                    👁️ Ver

                                </a>


                                @if($venta->estado)

                                    <form action="{{ route('ventas.anular', $venta) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('PUT')


                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Está seguro de anular esta venta?')">

                                            ❌ Anular

                                        </button>

                                    </form>

                                @endif


                            </div>

                        </td>


                    </tr>


                @empty


                    <tr>

                        <td colspan="9"
                            class="text-center py-4 text-muted">

                            No existen ventas registradas.

                        </td>

                    </tr>


                @endforelse


                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- PAGINACIÓN --}}

<div class="d-flex justify-content-center mt-4">

    {{ $ventas->onEachSide(1)->links('pagination::bootstrap-5') }}

</div>


@endsection
