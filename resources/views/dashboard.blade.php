@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    📊 Dashboard
</h2>


{{-- ===================================================== --}}
{{-- RESUMEN GENERAL --}}
{{-- ===================================================== --}}

<div class="row">


    {{-- VENTAS HOY --}}

    <div class="col-md-3 mb-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    💰 Ventas Hoy
                </h6>

                <h3 class="fw-bold text-success">

                    ${{ number_format($ventasHoy, 2, ',', '.') }}

                </h3>

                <small class="text-muted">
                    Total vendido
                </small>

            </div>

        </div>

    </div>



    {{-- CONTADO --}}

    <div class="col-md-3 mb-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    💵 Contado
                </h6>

                <h3 class="fw-bold">

                    ${{ number_format($ventasContadoHoy, 2, ',', '.') }}

                </h3>

                <small class="text-muted">
                    Ventas al contado de hoy
                </small>

            </div>

        </div>

    </div>



    {{-- FIADO --}}

    <div class="col-md-3 mb-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    📒 Fiado
                </h6>

                <h3 class="fw-bold text-warning">

                    ${{ number_format($ventasFiadoHoy, 2, ',', '.') }}

                </h3>

                <small class="text-muted">
                    Ventas fiadas de hoy
                </small>

            </div>

        </div>

    </div>



    {{-- SALDO PENDIENTE --}}

    <div class="col-md-3 mb-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    💳 Saldo Pendiente
                </h6>

                <h3 class="fw-bold text-danger">

                    ${{ number_format($saldoPendiente, 2, ',', '.') }}

                </h3>

                <small class="text-muted">
                    Total por cobrar
                </small>

            </div>

        </div>

    </div>


</div>



{{-- ===================================================== --}}
{{-- INFORMACIÓN GENERAL --}}
{{-- ===================================================== --}}

<div class="row">


    {{-- PRODUCTOS --}}

    <div class="col-md-3 mb-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">
                    📦 Productos
                </h6>

                <h3 class="fw-bold">

                    {{ $cantidadProductos }}

                </h3>

                <small class="text-muted">
                    Productos activos
                </small>

            </div>

        </div>

    </div>



    {{-- CLIENTES --}}

    <div class="col-md-3 mb-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">
                    👥 Clientes
                </h6>

                <h3 class="fw-bold">

                    {{ $cantidadClientes }}

                </h3>

                <small class="text-muted">
                    Clientes activos
                </small>

            </div>

        </div>

    </div>



    {{-- STOCK BAJO --}}

    <div class="col-md-3 mb-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">
                    ⚠️ Stock Bajo
                </h6>

                <h3 class="fw-bold text-danger">

                    {{ $productosStockBajo }}

                </h3>

                <small class="text-muted">
                    Productos para reponer
                </small>

            </div>

        </div>

    </div>


</div>



{{-- ===================================================== --}}
{{-- PEDIDOS --}}
{{-- ===================================================== --}}

<h4 class="mt-3 mb-3">
    📦 Estado de Pedidos
</h4>


<div class="row">


    {{-- PENDIENTES --}}

    <div class="col-md mb-3">

        <div class="card border-warning shadow-sm">

            <div class="card-body text-center">

                <h6>
                    🟡 Pendientes
                </h6>

                <h3>
                    {{ $pedidosPendientes }}
                </h3>

            </div>

        </div>

    </div>



    {{-- PREPARANDO --}}

    <div class="col-md mb-3">

        <div class="card border-info shadow-sm">

            <div class="card-body text-center">

                <h6>
                    🔵 Preparando
                </h6>

                <h3>
                    {{ $pedidosPreparando }}
                </h3>

            </div>

        </div>

    </div>



    {{-- LISTOS --}}

    <div class="col-md mb-3">

        <div class="card border-success shadow-sm">

            <div class="card-body text-center">

                <h6>
                    🟢 Listos
                </h6>

                <h3>
                    {{ $pedidosListos }}
                </h3>

            </div>

        </div>

    </div>



    {{-- ENTREGADOS --}}

    <div class="col-md mb-3">

        <div class="card border-success shadow-sm">

            <div class="card-body text-center">

                <h6>
                    ✅ Entregados
                </h6>

                <h3>
                    {{ $pedidosEntregados }}
                </h3>

            </div>

        </div>

    </div>



    {{-- CANCELADOS --}}

    <div class="col-md mb-3">

        <div class="card border-danger shadow-sm">

            <div class="card-body text-center">

                <h6>
                    🔴 Cancelados
                </h6>

                <h3>
                    {{ $pedidosCancelados }}
                </h3>

            </div>

        </div>

    </div>


</div>



{{-- ===================================================== --}}
{{-- STOCK BAJO --}}
{{-- ===================================================== --}}

<div class="row mt-4">


    <div class="col-md-6">

        <div class="card shadow-sm">

            <div class="card-header bg-warning">

                <strong>
                    ⚠️ Productos con Stock Bajo
                </strong>

            </div>


            <div class="card-body p-0">

                <table class="table table-bordered mb-0">

                    <thead>

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th>
                                Stock
                            </th>

                            <th>
                                Mínimo
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @forelse($stockBajo as $producto)

                        <tr>

                            <td>
                                {{ $producto->nombre }}
                            </td>

                            <td class="text-danger fw-bold">
                                {{ $producto->stock }}
                            </td>

                            <td>
                                {{ $producto->stock_minimo }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="text-center">

                                ✅ No hay productos con stock bajo.

                            </td>

                        </tr>

                    @endforelse


                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- ÚLTIMAS VENTAS --}}
    {{-- ================================================= --}}


    <div class="col-md-6">

        <div class="card shadow-sm">

            <div class="card-header">

                <strong>
                    🛒 Últimas Ventas
                </strong>

            </div>


            <div class="card-body p-0">

                <table class="table table-bordered mb-0">

                    <thead>

                        <tr>

                            <th>
                                Fecha
                            </th>

                            <th>
                                Cliente
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Pago
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @forelse($ultimasVentas as $venta)

                        <tr>

                            <td>

                                {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m H:i') }}

                            </td>


                            <td>

                                {{ $venta->cliente->nombre_completo }}

                            </td>


                            <td>

                                ${{ number_format($venta->total, 2, ',', '.') }}

                            </td>


                            <td>

                                @if($venta->tipo_pago == 'contado')

                                    <span class="badge bg-success">
                                        Contado
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Fiado
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center">

                                No existen ventas.

                            </td>

                        </tr>

                    @endforelse


                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>



{{-- ===================================================== --}}
{{-- ÚLTIMOS PEDIDOS --}}
{{-- ===================================================== --}}

<div class="card shadow-sm mt-4">


    <div class="card-header">

        <strong>
            📦 Últimos Pedidos
        </strong>

    </div>


    <div class="card-body p-0">

        <table class="table table-bordered mb-0">

            <thead class="table-dark">

                <tr>

                    <th>
                        Código
                    </th>

                    <th>
                        Cliente
                    </th>

                    <th>
                        Fecha
                    </th>

                    <th>
                        Total
                    </th>

                    <th>
                        Estado
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse($ultimosPedidos as $pedido)

                <tr>

                    <td>

                        {{ $pedido->codigo }}

                    </td>


                    <td>

                        {{ $pedido->cliente->nombre_completo }}

                    </td>


                    <td>

                        {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}

                    </td>


                    <td>

                        ${{ number_format($pedido->total, 2, ',', '.') }}

                    </td>


                    <td>


                        @if($pedido->estado == 'Pendiente')

                            <span class="badge bg-warning text-dark">
                                Pendiente
                            </span>

                        @elseif($pedido->estado == 'Preparando')

                            <span class="badge bg-info">
                                Preparando
                            </span>

                        @elseif($pedido->estado == 'Listo')

                            <span class="badge bg-success">
                                Listo
                            </span>

                        @elseif($pedido->estado == 'Entregado')

                            <span class="badge bg-success">
                                Entregado
                            </span>

                        @elseif($pedido->estado == 'Cancelado')

                            <span class="badge bg-danger">
                                Cancelado
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $pedido->estado }}
                            </span>

                        @endif


                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="5"
                        class="text-center">

                        No existen pedidos.

                    </td>

                </tr>

            @endforelse


            </tbody>

        </table>

    </div>

</div>


@endsection
