
@extends('layouts.cliente')

@section('contenido')

<div class="container py-4">

    {{-- Encabezado --}}
    <div class="text-center mb-4">

        <div
            class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center shadow"
            style="width: 80px; height: 80px; font-size: 38px;">
            📒
        </div>

        <h2 class="mt-3 mb-1">
            Estado de cuenta
        </h2>

        <p class="text-muted mb-0">
            Consultá tus compras fiadas, pagos y saldo pendiente
        </p>

    </div>


    {{-- Información del cliente y deuda --}}
    <div class="row justify-content-center mb-4">

        <div class="col-lg-9">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div class="row align-items-center">


                        {{-- Cliente --}}
                        <div class="col-md-6 mb-3 mb-md-0">

                            <div class="d-flex align-items-center">

                                <div
                                    class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                    style="width: 60px; height: 60px; font-size: 28px;">

                                    👤

                                </div>

                                <div>

                                    <small class="text-muted">
                                        Cliente
                                    </small>

                                    <h4 class="mb-0">

                                        {{ $cliente->nombre }}
                                        {{ $cliente->apellido }}

                                    </h4>

                                </div>

                            </div>

                        </div>


                        {{-- Deuda --}}
                        <div class="col-md-6">

                            <div class="text-md-end">

                                <small class="text-muted d-block">
                                    Deuda actual
                                </small>

                                @if($deuda > 0)

                                    <h2 class="text-danger mb-0">

                                        ${{ number_format($deuda, 2) }}

                                    </h2>

                                    <small class="text-danger">
                                        ⚠️ Saldo pendiente
                                    </small>

                                @else

                                    <h2 class="text-success mb-0">

                                        $0,00

                                    </h2>

                                    <small class="text-success">
                                        ✅ Cuenta al día
                                    </small>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Compras fiadas --}}
    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-primary text-white py-3">

                    <div class="d-flex align-items-center">

                        <div class="me-3 fs-3">
                            🛒
                        </div>

                        <div>

                            <h4 class="mb-0">
                                Compras fiadas
                            </h4>

                            <small>
                                Historial de compras realizadas a crédito
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body p-4">


                    @forelse($ventas as $venta)

                        <div class="card border mb-4">

                            {{-- Encabezado de compra --}}
                            <div class="card-header bg-light">

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                    <div>

                                        <strong>
                                            📅 Fecha de compra
                                        </strong>

                                        <div class="text-muted">

                                            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}

                                        </div>

                                    </div>


                                    @if($venta->saldo_pendiente > 0)

                                        <span class="badge bg-warning text-dark">

                                            ⏳ Pendiente

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            ✅ Pagada

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Productos --}}
                            <div class="card-body">

                                <h6 class="mb-3">
                                    🥖 Productos
                                </h6>

                                <div class="table-responsive">

                                    <table class="table table-sm align-middle mb-3">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Producto
                                                </th>

                                                <th class="text-center">
                                                    Cantidad
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach($venta->detalles as $detalle)

                                                <tr>

                                                    <td>
                                                        {{ $detalle->producto->nombre }}
                                                    </td>

                                                    <td class="text-center">

                                                        <span class="badge bg-secondary">

                                                            {{ $detalle->cantidad }}

                                                        </span>

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>


                                {{-- Totales --}}
                                <div class="border-top pt-3">

                                    <div class="row">

                                        <div class="col-md-6 mb-2">

                                            <small class="text-muted d-block">
                                                Total de la compra
                                            </small>

                                            <strong class="fs-5">

                                                ${{ number_format($venta->total, 2) }}

                                            </strong>

                                        </div>


                                        <div class="col-md-6 mb-2">

                                            <small class="text-muted d-block">
                                                Saldo pendiente
                                            </small>


                                            @if($venta->saldo_pendiente > 0)

                                                <strong class="fs-5 text-danger">

                                                    ${{ number_format($venta->saldo_pendiente, 2) }}

                                                </strong>

                                            @else

                                                <strong class="fs-5 text-success">

                                                    $0,00

                                                </strong>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-4">

                            <div style="font-size: 50px;">
                                🎉
                            </div>

                            <h5 class="mt-3">
                                No tenés compras fiadas
                            </h5>

                            <p class="text-muted mb-0">
                                Tu cuenta no tiene compras pendientes.
                            </p>

                        </div>

                    @endforelse


                </div>

            </div>


            {{-- Pagos realizados --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-success text-white py-3">

                    <div class="d-flex align-items-center">

                        <div class="me-3 fs-3">
                            💰
                        </div>

                        <div>

                            <h4 class="mb-0">
                                Pagos realizados
                            </h4>

                            <small>
                                Historial de pagos registrados
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body p-4">


                    @forelse($pagos as $pago)

                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                            <div>

                                <strong>
                                    💰 Pago registrado
                                </strong>

                                <div class="text-muted small">

                                    📅
                                    {{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}

                                </div>


                                @if($pago->observacion)

                                    <div class="small mt-1">

                                        📝 {{ $pago->observacion }}

                                    </div>

                                @endif

                            </div>


                            <div class="text-end">

                                <strong class="text-success fs-5">

                                    ${{ number_format($pago->monto, 2) }}

                                </strong>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-4">

                            <div style="font-size: 45px;">
                                💰
                            </div>

                            <h5 class="mt-3">
                                No hay pagos registrados
                            </h5>

                            <p class="text-muted mb-0">
                                Todavía no tenés pagos registrados en tu cuenta.
                            </p>

                        </div>

                    @endforelse


                </div>

            </div>


            {{-- Volver --}}
            <div class="text-center">

                <a
                    href="{{ route('clientes.perfil') }}"
                    class="btn btn-outline-secondary">

                    ↩️ Volver a mi perfil

                </a>

            </div>

        </div>

    </div>

</div>

@endsection

