@extends('layouts.app')

@section('contenido')

<style>

    .dashboard-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .dashboard-link .card {
        transition: all 0.2s ease;
    }

    .dashboard-link:hover .card {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.12) !important;
    }

    .dashboard-link:hover {
        color: inherit;
    }

</style>


<h2 class="mb-4">
    📊 Dashboard
</h2>


{{-- ===================================================== --}}
{{-- RESUMEN GENERAL --}}
{{-- ===================================================== --}}

<div class="row">


    {{-- VENTAS HOY --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('ventas.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        💰 Ventas Hoy
                    </h6>

                    <h3 class="fw-bold text-success">

                        ${{ number_format($ventasHoy, 2, ',', '.') }}

                    </h3>

                    <small class="text-muted">
                        Ver todas las ventas →
                    </small>

                </div>

            </div>

        </a>

    </div>


    {{-- CONTADO --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('ventas.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        💵 Contado
                    </h6>

                    <h3 class="fw-bold">

                        ${{ number_format($ventasContadoHoy, 2, ',', '.') }}

                    </h3>

                    <small class="text-muted">
                        Ventas al contado →
                    </small>

                </div>

            </div>

        </a>

    </div>


    {{-- FIADO --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('ventas.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        📒 Fiado
                    </h6>

                    <h3 class="fw-bold text-warning">

                        ${{ number_format($ventasFiadoHoy, 2, ',', '.') }}

                    </h3>

                    <small class="text-muted">
                        Ventas fiadas →
                    </small>

                </div>

            </div>

        </a>

    </div>


    {{-- SALDO PENDIENTE --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('clientes.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        💳 Saldo Pendiente
                    </h6>

                    <h3 class="fw-bold text-danger">

                        ${{ number_format($saldoPendiente, 2, ',', '.') }}

                    </h3>

                    <small class="text-muted">
                        Ver clientes →
                    </small>

                </div>

            </div>

        </a>

    </div>

</div>



{{-- ===================================================== --}}
{{-- INFORMACIÓN GENERAL --}}
{{-- ===================================================== --}}

<div class="row">


    {{-- PRODUCTOS --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('productos.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        📦 Productos
                    </h6>

                    <h3 class="fw-bold">

                        {{ $cantidadProductos }}

                    </h3>

                    <small class="text-muted">
                        Ver productos →
                    </small>

                </div>

            </div>

        </a>

    </div>


    {{-- CLIENTES --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('clientes.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        👥 Clientes
                    </h6>

                    <h3 class="fw-bold">

                        {{ $cantidadClientes }}

                    </h3>

                    <small class="text-muted">
                        Ver clientes →
                    </small>

                </div>

            </div>

        </a>

    </div>


    {{-- STOCK BAJO --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('productos.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        ⚠️ Stock Bajo
                    </h6>

                    <h3 class="fw-bold text-danger">

                        {{ $productosStockBajo }}

                    </h3>

                    <small class="text-muted">
                        Revisar productos →
                    </small>

                </div>

            </div>

        </a>

    </div>


    {{-- PEDIDOS --}}

    <div class="col-md-3 mb-4">

        <a href="{{ route('pedidos.index') }}"
           class="dashboard-link">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        📦 Pedidos
                    </h6>

                    <h3 class="fw-bold">

                        {{ $pedidosPendientes }}

                    </h3>

                    <small class="text-muted">
                        Pedidos pendientes →
                    </small>

                </div>

            </div>

        </a>

    </div>


</div>



{{-- ===================================================== --}}
{{-- ESTADO DE PEDIDOS --}}
{{-- ===================================================== --}}

<h4 class="mt-3 mb-3">
    📦 Estado de Pedidos
</h4>


<div class="row">


    <div class="col-md mb-3">

        <a href="{{ route('pedidos.index') }}"
           class="dashboard-link">

            <div class="card border-warning shadow-sm">

                <div class="card-body text-center">

                    <h6>
                        🟡 Pendientes
                    </h6>

                    <h3>
                        {{ $pedidosPendientes }}
                    </h3>

                    <small class="text-muted">
                        Ver pedidos →
                    </small>

                </div>

            </div>

        </a>

    </div>



    <div class="col-md mb-3">

        <a href="{{ route('pedidos.index') }}"
           class="dashboard-link">

            <div class="card border-info shadow-sm">

                <div class="card-body text-center">

                    <h6>
                        🔵 Preparando
                    </h6>

                    <h3>
                        {{ $pedidosPreparando }}
                    </h3>

                    <small class="text-muted">
                        Ver pedidos →
                    </small>

                </div>

            </div>

        </a>

    </div>



    <div class="col-md mb-3">

        <a href="{{ route('pedidos.index') }}"
           class="dashboard-link">

            <div class="card border-success shadow-sm">

                <div class="card-body text-center">

                    <h6>
                        🟢 Listos
                    </h6>

                    <h3>
                        {{ $pedidosListos }}
                    </h3>

                    <small class="text-muted">
                        Ver pedidos →
                    </small>

                </div>

            </div>

        </a>

    </div>



    <div class="col-md mb-3">

        <a href="{{ route('pedidos.index') }}"
           class="dashboard-link">

            <div class="card border-success shadow-sm">

                <div class="card-body text-center">

                    <h6>
                        ✅ Entregados
                    </h6>

                    <h3>
                        {{ $pedidosEntregados }}
                    </h3>

                    <small class="text-muted">
                        Ver pedidos →
                    </small>

                </div>

            </div>

        </a>

    </div>



    <div class="col-md mb-3">

        <a href="{{ route('pedidos.index') }}"
           class="dashboard-link">

            <div class="card border-danger shadow-sm">

                <div class="card-body text-center">

                    <h6>
                        🔴 Cancelados
                    </h6>

                    <h3>
                        {{ $pedidosCancelados }}
                    </h3>

                    <small class="text-muted">
                        Ver pedidos →
                    </small>

                </div>

            </div>

        </a>

    </div>


</div>



{{-- ===================================================== --}}
{{-- STOCK BAJO + ÚLTIMAS VENTAS --}}
{{-- ===================================================== --}}

<div class="row mt-4">


    {{-- STOCK BAJO --}}

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

                                <a href="{{ route('productos.show', $producto) }}"
                                   class="text-decoration-none">

                                    {{ $producto->nombre }}

                                </a>

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

                            <td colspan="3"
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



    {{-- ÚLTIMAS VENTAS --}}

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

                                <a href="{{ route('ventas.show', $venta) }}"
                                   class="text-decoration-none">

                                    {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m H:i') }}

                                </a>

                            </td>


                            <td>

                                <a href="{{ route('clientes.show', $venta->cliente) }}"
                                   class="text-decoration-none">

                                    {{ $venta->cliente->nombre_completo }}

                                </a>

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

                            <td colspan="4"
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

                        <a href="{{ route('pedidos.show', $pedido) }}"
                           class="text-decoration-none fw-bold">

                            {{ $pedido->codigo }}

                        </a>

                    </td>


                    <td>

                        <a href="{{ route('clientes.show', $pedido->cliente) }}"
                           class="text-decoration-none">

                            {{ $pedido->cliente->nombre_completo }}

                        </a>

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

                    <td colspan="5"
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
