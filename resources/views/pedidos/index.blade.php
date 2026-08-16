@extends('layouts.app')

@section('contenido')

<div class="container-fluid">

    {{-- ============================================================
         TÍTULO
    ============================================================ --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            📦 Pedidos
        </h2>

        <p class="text-muted mb-0">
            Administración y seguimiento de pedidos
        </p>

    </div>

    <div>
         <a
            href="{{ route('pedidos.produccion') }}"
            class="btn btn-warning"
        >
            📊 Producción
        </a>
        <a
            href="{{ route('pedidos.create') }}"
            class="btn btn-success">

            ➕ Nuevo pedido

        </a>

    </div>

</div>


    {{-- ============================================================
         MENSAJE DE ÉXITO
    ============================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
         CONTADORES
    ============================================================ --}}

    <div class="row g-3 mb-4">


        {{-- TOTAL --}}

        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <h3 class="mb-0">
                        {{ $totalPedidos }}
                    </h3>

                    <small class="text-muted">
                        pedidos
                    </small>

                </div>

            </div>

        </div>


        {{-- PENDIENTES --}}

        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-warning h-100">

                <div class="card-body">

                    <div class="text-warning small">
                        🟡 Pendientes
                    </div>

                    <h3 class="mb-0">
                        {{ $pendientes }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- PREPARANDO --}}

        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-primary h-100">

                <div class="card-body">

                    <div class="text-primary small">
                        🔵 Preparando
                    </div>

                    <h3 class="mb-0">
                        {{ $preparando }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- LISTOS --}}

        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-success h-100">

                <div class="card-body">

                    <div class="text-success small">
                        🟢 Listos
                    </div>

                    <h3 class="mb-0">
                        {{ $listos }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- ENTREGADOS --}}

        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-success h-100">

                <div class="card-body">

                    <div class="text-success small">
                        ✅ Entregados
                    </div>

                    <h3 class="mb-0">
                        {{ $entregados }}
                    </h3>

                </div>

            </div>

        </div>


        {{-- CANCELADOS --}}

        <div class="col-md-4 col-lg-2">

            <div class="card shadow-sm border-danger h-100">

                <div class="card-body">

                    <div class="text-danger small">
                        ❌ Cancelados
                    </div>

                    <h3 class="mb-0">
                        {{ $cancelados }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         FILTROS
    ============================================================ --}}

<div class="card shadow mb-4">

    <div class="card-body">

        <form
            action="{{ route('pedidos.index') }}"
            method="GET"
        >

            <div class="row g-3 align-items-end">

                {{-- BUSCAR --}}

                <div class="col-md-4">

                    <label class="form-label">
                        🔎 Buscar pedido
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Código o nombre del cliente..."
                        value="{{ request('buscar') }}"
                    >

                </div>


                {{-- DIRECCIÓN --}}

                <div class="col-md-3">

                    <label class="form-label">
                        📍 Dirección
                    </label>

                    <input
                        type="text"
                        name="direccion"
                        class="form-control"
                        placeholder="Ej: Piedras Coloradas"
                        value="{{ request('direccion') }}"
                    >

                </div>


                {{-- ESTADO --}}

                <div class="col-md-2">

                    <label class="form-label">
                        📊 Estado
                    </label>

                    <select
                        name="estado"
                        class="form-select"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="Pendiente"
                            {{ request('estado') == 'Pendiente' ? 'selected' : '' }}
                        >
                            🟡 Pendiente
                        </option>

                        <option
                            value="Preparando"
                            {{ request('estado') == 'Preparando' ? 'selected' : '' }}
                        >
                            🔵 Preparando
                        </option>

                        <option
                            value="Listo"
                            {{ request('estado') == 'Listo' ? 'selected' : '' }}
                        >
                            🟢 Listo
                        </option>

                        <option
                            value="Entregado"
                            {{ request('estado') == 'Entregado' ? 'selected' : '' }}
                        >
                            ✅ Entregado
                        </option>

                        <option
                            value="Cancelado"
                            {{ request('estado') == 'Cancelado' ? 'selected' : '' }}
                        >
                            ❌ Cancelado
                        </option>

                    </select>

                </div>


                {{-- FECHA --}}

                <div class="col-md-2">

                    <label class="form-label">
                        📅 Fecha
                    </label>

                    <input
                        type="date"
                        name="fecha"
                        class="form-control"
                        value="{{ request('fecha') }}"
                    >

                </div>


                {{-- BOTÓN BUSCAR --}}

                <div class="col-md-1">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >

                        🔍

                    </button>

                </div>

            </div>

        </form>


        {{-- BOTÓN LIMPIAR --}}

        <div class="mt-2">

            <a
                href="{{ route('pedidos.index') }}"
                class="btn btn-outline-secondary btn-sm"
            >

                ↺ Limpiar filtros

            </a>

        </div>

    </div>

</div>

{{-- RESUMEN DEL FILTRO --}}

@if(request('buscar') || request('direccion') || request('estado') || request('fecha'))

<div class="card shadow mb-4">

    <div class="card-header bg-primary text-white">

        <strong>
            📦 Resumen de los pedidos filtrados
        </strong>

    </div>


    <div class="card-body">


        {{-- ESTADÍSTICAS --}}

        <div class="row mb-4">


            <div class="col-md-4">

                <div class="card border-primary">

                    <div class="card-body text-center">

                        <div class="text-muted small">

                            Pedidos encontrados

                        </div>

                        <h3 class="mb-0">

                            {{ $pedidosFiltrados }}

                        </h3>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card border-success">

                    <div class="card-body text-center">

                        <div class="text-muted small">

                            Unidades solicitadas

                        </div>

                        <h3 class="mb-0">

                            {{ $unidadesFiltradas }}

                        </h3>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card border-warning">

                    <div class="card-body text-center">

                        <div class="text-muted small">

                            Total de pedidos

                        </div>

                        <h3 class="mb-0 text-success">

                            ${{ number_format($totalFiltrado, 2) }}

                        </h3>

                    </div>

                </div>

            </div>


        </div>


        {{-- PRODUCTOS --}}

        <h5 class="mb-3">

            📋 Productos solicitados

        </h5>


        @if($resumenProductos->count())


            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th
                                class="text-center"
                                width="180"
                            >
                                Cantidad
                            </th>

                            <th
                                class="text-end"
                                width="180"
                            >
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($resumenProductos as $item)


                        <tr>

                            <td>

                                <strong>

                                    {{ $item['producto']->nombre }}

                                </strong>

                            </td>


                            <td class="text-center">

                                <span class="badge bg-primary fs-6">

                                    {{ $item['cantidad'] }}

                                </span>

                            </td>


                            <td class="text-end">

                                ${{ number_format(
                                    $item['subtotal'],
                                    2
                                ) }}

                            </td>

                        </tr>


                    @endforeach


                    </tbody>

                </table>

            </div>


        @else

            <div class="alert alert-info mb-0">

                📭 No se encontraron productos para los filtros seleccionados.

            </div>

        @endif


    </div>

</div>

@endif

    {{-- ============================================================
         TABLA
    ============================================================ --}}

    @if($pedidos->count())

        <div class="card shadow">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Código
                                </th>

                                <th>
                                    Cliente
                                </th>

                                <th>
                                    Entrega
                                </th>

                                <th>
                                    Tipo
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th>
                                    Venta
                                </th>

                                <th class="text-center">
                                    Acción
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($pedidos as $pedido)

                            <tr>


                                {{-- CÓDIGO --}}

                                <td class="align-middle">

                                    <strong>
                                        {{ $pedido->codigo }}
                                    </strong>

                                </td>


                                {{-- CLIENTE --}}

                                <td class="align-middle">

                                    @if($pedido->cliente)

                                        <strong>
                                            {{ $pedido->cliente->nombre_completo }}
                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            Cliente eliminado
                                        </span>

                                    @endif

                                </td>


                               {{-- ENTREGA --}}

<td class="align-middle">

    <strong>

        {{ \Carbon\Carbon::parse(
            $pedido->fecha_entrega
        )->format('d/m/Y') }}

    </strong>

    <br>

    <small class="text-muted">

        🕐 {{ $pedido->hora_entrega }}

    </small>


    @if($pedido->direccion_entrega)

        <br>

        <small class="text-primary">

            📍 {{ $pedido->direccion_entrega }}

        </small>

    @endif

</td>


                                {{-- TIPO DE ENTREGA --}}

                                <td class="align-middle">

                                    @if($pedido->tipo_entrega == 'delivery')

                                        🚚 Delivery

                                    @elseif($pedido->tipo_entrega == 'retiro')

                                        🏪 Retiro

                                    @else

                                        {{ $pedido->tipo_entrega }}

                                    @endif

                                </td>


                                {{-- TOTAL --}}

                                <td class="align-middle">

                                    <strong>

                                        ${{ number_format(
                                            $pedido->total,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- ESTADO --}}

                                <td class="align-middle">

                                    <form
                                        action="{{ route(
                                            'pedidos.estado',
                                            $pedido
                                        ) }}"
                                        method="POST">

                                        @csrf

                                        @method('PATCH')


                                        <select
                                            name="estado"
                                            class="form-select form-select-sm"
                                            onchange="this.form.submit()">


                                            <option
                                                value="Pendiente"
                                                {{ $pedido->estado == 'Pendiente' ? 'selected' : '' }}>

                                                🟡 Pendiente

                                            </option>


                                            <option
                                                value="Preparando"
                                                {{ $pedido->estado == 'Preparando' ? 'selected' : '' }}>

                                                🔵 Preparando

                                            </option>


                                            <option
                                                value="Listo"
                                                {{ $pedido->estado == 'Listo' ? 'selected' : '' }}>

                                                🟢 Listo

                                            </option>


                                            <option
                                                value="Entregado"
                                                {{ $pedido->estado == 'Entregado' ? 'selected' : '' }}>

                                                ✅ Entregado

                                            </option>


                                            <option
                                                value="Cancelado"
                                                {{ $pedido->estado == 'Cancelado' ? 'selected' : '' }}>

                                                ❌ Cancelado

                                            </option>

                                        </select>

                                    </form>

                                </td>


                                {{-- VENTA --}}

                                <td class="align-middle">

                                    @if($pedido->venta)

                                        <span class="badge bg-success">

                                            ✓ Generada

                                        </span>

                                    @elseif($pedido->estado == 'Entregado')

                                        <span class="badge bg-warning text-dark">

                                            ⚠ Sin venta

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            No corresponde

                                        </span>

                                    @endif

                                </td>


                                {{-- ACCIÓN --}}

                                <td class="align-middle text-center">

                                    <a
                                        href="{{ route(
                                            'pedidos.show',
                                            $pedido
                                        ) }}"
                                        class="btn btn-primary btn-sm">

                                        👁 Ver

                                    </a>

                                </td>


                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PAGINACIÓN --}}

            <div class="card-footer">

                {{ $pedidos->links() }}

            </div>

        </div>


    @else

        <div class="alert alert-info shadow-sm">

            <h5 class="mb-1">
                📦 No encontramos pedidos
            </h5>

            <p class="mb-0">

                No existen pedidos que coincidan con los filtros seleccionados.

            </p>

        </div>

    @endif

</div>

@endsection
