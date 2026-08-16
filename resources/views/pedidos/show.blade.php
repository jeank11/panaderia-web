@extends('layouts.app')

@section('contenido')

<div class="container-fluid">


    {{-- ============================================================
         ENCABEZADO
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">
                📦 Pedido {{ $pedido->codigo }}
            </h2>

            <p class="text-muted mb-0">
                Detalle completo del pedido
            </p>

        </div>


        <div class="d-flex gap-2">

            <button
                onclick="window.print()"
                class="btn btn-outline-dark">

                🖨️ Imprimir

            </button>


            <a
                href="{{ route('pedidos.index') }}"
                class="btn btn-secondary">

                ↩️ Volver

            </a>

        </div>

    </div>



    {{-- ============================================================
         ESTADO DEL PEDIDO
    ============================================================ --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Estado actual
                    </div>

                    @if($pedido->estado == 'Pendiente')

                        <span class="badge bg-warning text-dark fs-6">
                            🟡 Pendiente
                        </span>

                    @elseif($pedido->estado == 'Preparando')

                        <span class="badge bg-primary fs-6">
                            🔵 Preparando
                        </span>

                    @elseif($pedido->estado == 'Listo')

                        <span class="badge bg-success fs-6">
                            🟢 Listo
                        </span>

                    @elseif($pedido->estado == 'Entregado')

                        <span class="badge bg-success fs-6">
                            ✅ Entregado
                        </span>

                    @elseif($pedido->estado == 'Cancelado')

                        <span class="badge bg-danger fs-6">
                            ❌ Cancelado
                        </span>

                    @else

                        <span class="badge bg-secondary fs-6">
                            {{ $pedido->estado }}
                        </span>

                    @endif

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Fecha del pedido
                    </div>

                    <strong>

                        {{ \Carbon\Carbon::parse(
                            $pedido->fecha_pedido
                        )->format('d/m/Y H:i') }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Fecha de entrega
                    </div>

                    <strong>

                        {{ \Carbon\Carbon::parse(
                            $pedido->fecha_entrega
                        )->format('d/m/Y') }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Hora de entrega
                    </div>

                    <strong>

                        🕐 {{ $pedido->hora_entrega }}

                    </strong>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         SEGUIMIENTO
    ============================================================ --}}

    @if($pedido->estado != 'Cancelado')

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white">

                📊 Seguimiento del pedido

            </div>


            <div class="card-body">

                <div class="row text-center">


                    {{-- PENDIENTE --}}

                    <div class="col">

                        <div class="
                            p-3
                            rounded
                            {{ in_array(
                                $pedido->estado,
                                ['Pendiente','Preparando','Listo','Entregado']
                            )
                            ? 'bg-warning-subtle'
                            : 'bg-light' }}
                        ">

                            <div class="fs-3">
                                🟡
                            </div>

                            <strong>
                                Pendiente
                            </strong>

                        </div>

                    </div>


                    {{-- FLECHA --}}

                    <div class="col-auto d-flex align-items-center">

                        ➜

                    </div>


                    {{-- PREPARANDO --}}

                    <div class="col">

                        <div class="
                            p-3
                            rounded
                            {{ in_array(
                                $pedido->estado,
                                ['Preparando','Listo','Entregado']
                            )
                            ? 'bg-primary-subtle'
                            : 'bg-light' }}
                        ">

                            <div class="fs-3">
                                🔵
                            </div>

                            <strong>
                                Preparando
                            </strong>

                        </div>

                    </div>


                    <div class="col-auto d-flex align-items-center">

                        ➜

                    </div>


                    {{-- LISTO --}}

                    <div class="col">

                        <div class="
                            p-3
                            rounded
                            {{ in_array(
                                $pedido->estado,
                                ['Listo','Entregado']
                            )
                            ? 'bg-success-subtle'
                            : 'bg-light' }}
                        ">

                            <div class="fs-3">
                                🟢
                            </div>

                            <strong>
                                Listo
                            </strong>

                        </div>

                    </div>


                    <div class="col-auto d-flex align-items-center">

                        ➜

                    </div>


                    {{-- ENTREGADO --}}

                    <div class="col">

                        <div class="
                            p-3
                            rounded
                            {{ $pedido->estado == 'Entregado'
                            ? 'bg-success-subtle'
                            : 'bg-light' }}
                        ">

                            <div class="fs-3">
                                ✅
                            </div>

                            <strong>
                                Entregado
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="alert alert-danger shadow-sm">

            ❌ Este pedido fue cancelado.

        </div>

    @endif



    <div class="row">


        {{-- ========================================================
             DATOS DEL CLIENTE
        ========================================================= --}}

        <div class="col-lg-6">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-primary text-white">

                    👤 Datos del cliente

                </div>


                <div class="card-body">

                    @if($pedido->cliente)

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <div class="text-muted small">
                                    Nombre
                                </div>

                                <strong>

                                    {{ $pedido->cliente->nombre_completo }}

                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <div class="text-muted small">
                                    Documento
                                </div>

                                <strong>

                                    {{ $pedido->cliente->documento ?? 'No registrado' }}

                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <div class="text-muted small">
                                    Teléfono
                                </div>

                                <strong>

                                    {{ $pedido->cliente->telefono ?? 'No registrado' }}

                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <div class="text-muted small">
                                    Email
                                </div>

                                <strong>

                                    {{ $pedido->cliente->email ?? 'No registrado' }}

                                </strong>

                            </div>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">

                            ⚠️ El cliente asociado a este pedido ya no está disponible.

                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- ========================================================
             DATOS DE ENTREGA
        ========================================================= --}}

        <div class="col-lg-6">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-warning">

                    🚚 Datos de entrega

                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">
                                Tipo de entrega
                            </div>

                            <strong>

                                @if($pedido->tipo_entrega == 'delivery')

                                    🚚 Delivery

                                @elseif($pedido->tipo_entrega == 'retiro')

                                    🏪 Retiro

                                @else

                                    {{ $pedido->tipo_entrega }}

                                @endif

                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">
                                Fecha
                            </div>

                            <strong>

                                {{ \Carbon\Carbon::parse(
                                    $pedido->fecha_entrega
                                )->format('d/m/Y') }}

                            </strong>

                        </div>


                        <div class="col-md-6 mb-3">

                            <div class="text-muted small">
                                Hora
                            </div>

                            <strong>

                                🕐 {{ $pedido->hora_entrega }}

                            </strong>

                        </div>


                        @if($pedido->direccion_entrega)

                            <div class="col-12 mb-3">

                                <div class="text-muted small">
                                    Dirección de entrega
                                </div>

                                <strong>

                                    📍 {{ $pedido->direccion_entrega }}

                                </strong>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         OBSERVACIONES
    ============================================================ --}}

    @if($pedido->observaciones)

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-info text-white">

                📝 Observaciones

            </div>

            <div class="card-body">

                {{ $pedido->observaciones }}

            </div>

        </div>

    @endif



    {{-- ============================================================
         PRODUCTOS
    ============================================================ --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-dark text-white">

            🛒 Productos del pedido

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th class="text-center">
                                Cantidad
                            </th>

                            <th class="text-end">
                                Precio unitario
                            </th>

                            <th class="text-end">
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($pedido->detalles as $detalle)

                        <tr>

                            <td>

                                <strong>

                                    {{ $detalle->producto->nombre }}

                                </strong>

                                @if($detalle->producto->codigo ?? false)

                                    <br>

                                    <small class="text-muted">

                                        Código:
                                        {{ $detalle->producto->codigo }}

                                    </small>

                                @endif

                            </td>


                            <td class="text-center">

                                <span class="badge bg-secondary">

                                    {{ $detalle->cantidad }}

                                </span>

                            </td>


                            <td class="text-end">

                                ${{ number_format(
                                    $detalle->precio,
                                    2
                                ) }}

                            </td>


                            <td class="text-end">

                                <strong>

                                    ${{ number_format(
                                        $detalle->subtotal,
                                        2
                                    ) }}

                                </strong>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- TOTAL --}}

        <div class="card-footer">

            <div class="row justify-content-end">

                <div class="col-md-4">

                    <div class="d-flex justify-content-between">

                        <span>
                            Total del pedido:
                        </span>

                        <strong class="fs-4 text-success">

                            ${{ number_format(
                                $pedido->total,
                                2
                            ) }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         INFORMACIÓN DE VENTA
    ============================================================ --}}

    @if($pedido->venta)

        <div class="card shadow-sm border-success mb-4">

            <div class="card-header bg-success text-white">

                🧾 Venta asociada

            </div>


            <div class="card-body">

                <div class="row align-items-center">


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Venta
                        </div>

                        <strong>
                            #{{ $pedido->venta->id }}
                        </strong>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Fecha
                        </div>

                        <strong>

                            {{ \Carbon\Carbon::parse(
                                $pedido->venta->fecha
                            )->format('d/m/Y H:i') }}

                        </strong>

                    </div>


                    <div class="col-md-4 text-md-end">

                        @if(Route::has('ventas.show'))

                            <a
                                href="{{ route(
                                    'ventas.show',
                                    $pedido->venta
                                ) }}"
                                class="btn btn-success">

                                🧾 Ver venta

                            </a>

                        @else

                            <span class="badge bg-success fs-6">

                                ✓ Venta generada

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @elseif($pedido->estado == 'Entregado')

        <div class="alert alert-warning shadow-sm">

            ⚠️ El pedido figura como entregado,
            pero todavía no tiene una venta asociada.

        </div>

    @endif



    {{-- ============================================================
         BOTONES INFERIORES
    ============================================================ --}}

    <div class="d-flex justify-content-between mb-4">

        <a
            href="{{ route('pedidos.index') }}"
            class="btn btn-secondary">

            ↩️ Volver a pedidos

        </a>


        <button
            onclick="window.print()"
            class="btn btn-outline-dark">

            🖨️ Imprimir pedido

        </button>

    </div>


</div>


{{-- ================================================================
     ESTILOS PARA IMPRESIÓN
================================================================ --}}

<style>

@media print {

    .navbar,
    .sidebar,
    .btn,
    form,
    footer {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

}

</style>


@endsection

