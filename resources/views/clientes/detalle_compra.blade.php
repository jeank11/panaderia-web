@extends('layouts.cliente')

@section('contenido')

<div class="container-fluid px-0">

    {{-- ENCABEZADO --}}

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                🧾 Detalle de Compra

            </h2>

            <p class="text-muted mb-0">

                Información completa de tu compra.

            </p>

        </div>


        <a
            href="{{ route('clientes.productos') }}"
            class="btn btn-success">

            🛍️ Seguir comprando

        </a>

    </div>



    {{-- ENCABEZADO DE LA COMPRA --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row align-items-center">


                <div class="col-md-6 mb-3 mb-md-0">

                    <small class="text-muted d-block">

                        Número de compra

                    </small>

                    <h3 class="fw-bold mb-0">

                        🧾 #{{ $venta->id }}

                    </h3>

                </div>


                <div class="col-md-6 text-md-end">

                    <small class="text-muted d-block">

                        Estado

                    </small>


                    @if($venta->estado)

                        <span class="badge bg-success fs-6 px-3 py-2">

                            ✅ Compra activa

                        </span>

                    @else

                        <span class="badge bg-danger fs-6 px-3 py-2">

                            ❌ Compra anulada

                        </span>

                    @endif

                </div>


            </div>

        </div>

    </div>



    {{-- INFORMACIÓN --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-0">

                📋 Información de la compra

            </h5>

        </div>


        <div class="card-body px-4">

            <div class="row g-3">


                <div class="col-md-4">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Fecha

                        </small>

                        <strong>

                            📅

                            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}

                        </strong>

                    </div>

                </div>



                <div class="col-md-4">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Forma de pago

                        </small>


                        @if($venta->tipo_pago == 'fiado')

                            <strong class="text-warning">

                                📒 Fiado

                            </strong>

                        @else

                            <strong class="text-success">

                                💵 Contado

                            </strong>

                        @endif

                    </div>

                </div>



                <div class="col-md-4">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Estado del pago

                        </small>


                        @if($venta->estado_pago == 'pagada')

                            <span class="badge bg-success">

                                ✅ Pagada

                            </span>

                        @elseif($venta->estado_pago == 'parcial')

                            <span class="badge bg-warning text-dark">

                                🟡 Pago parcial

                            </span>

                        @else

                            <span class="badge bg-danger">

                                🔴 Pendiente

                            </span>

                        @endif

                    </div>

                </div>


            </div>


            {{-- PEDIDO RELACIONADO --}}

            @if($venta->pedido)

                <div class="info-box mt-3">

                    <small class="text-muted d-block">

                        Pedido relacionado

                    </small>

                    <strong>

                        📦 {{ $venta->pedido->codigo }}

                    </strong>

                </div>

            @endif

        </div>

    </div>



    {{-- PRODUCTOS --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-0">

                🛒 Productos comprados

            </h5>

        </div>


        <div class="card-body px-4">


            @forelse($venta->detalles as $detalle)


                <div class="producto-compra">

                    <div>

                        <h6 class="fw-bold mb-1">

                            {{ $detalle->producto->nombre ?? 'Producto eliminado' }}

                        </h6>

                        <small class="text-muted">

                            {{ $detalle->cantidad }}

                            ×

                            ${{ number_format($detalle->precio,2) }}

                        </small>

                    </div>


                    <strong class="text-success">

                        ${{ number_format($detalle->subtotal,2) }}

                    </strong>

                </div>


            @empty

                <div class="alert alert-info mb-0 text-center">

                    No hay productos registrados en esta compra.

                </div>

            @endforelse


            <hr>


            {{-- TOTAL --}}

            <div class="d-flex justify-content-between align-items-center">

                <span class="fs-5 fw-bold">

                    Total

                </span>

                <strong class="total-compra">

                    ${{ number_format($venta->total,2) }}

                </strong>

            </div>


        </div>

    </div>



    {{-- INFORMACIÓN DE DEUDA --}}

    @if($venta->tipo_pago == 'fiado' && $venta->saldo_pendiente > 0)

        <div class="card border-warning shadow-sm mb-4">

            <div class="card-body">

                <div class="row align-items-center">


                    <div class="col-md-8">

                        <h5 class="fw-bold text-warning">

                            📒 Compra fiada

                        </h5>

                        <p class="mb-0 text-muted">

                            Esta compra tiene un saldo pendiente.

                        </p>

                    </div>


                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        <small class="text-muted d-block">

                            Saldo pendiente

                        </small>

                        <strong class="saldo-pendiente">

                            ${{ number_format($venta->saldo_pendiente,2) }}

                        </strong>

                    </div>


                </div>

            </div>

        </div>

    @elseif($venta->tipo_pago == 'fiado')

        <div class="alert alert-success shadow-sm">

            <strong>

                ✅ Compra fiada totalmente pagada.

            </strong>

        </div>

    @endif



    {{-- BOTONES --}}

    <div class="d-flex flex-column flex-md-row gap-2 mb-4">

        <a
            href="{{ route('clientes.compras') }}"
            class="btn btn-outline-secondary">

            ← Volver a mis compras

        </a>


        <a
            href="{{ route('clientes.productos') }}"
            class="btn btn-success">

            🛍️ Hacer una nueva compra

        </a>

    </div>

</div>



<style>

    .info-box{

        background:#f8f9fa;

        border-radius:12px;

        padding:15px;

        height:100%;

    }


    .producto-compra{

        display:flex;

        justify-content:space-between;

        align-items:center;

        gap:15px;

        padding:16px 5px;

        border-bottom:1px solid #eeeeee;

    }


    .producto-compra:last-child{

        border-bottom:none;

    }


    .total-compra{

        font-size:28px;

        color:#198754;

    }


    .saldo-pendiente{

        font-size:25px;

        color:#dc3545;

    }


    @media(max-width:576px){

        .producto-compra{

            padding:14px 0;

        }


        .total-compra{

            font-size:24px;

        }


        .saldo-pendiente{

            font-size:22px;

        }

    }

</style>

@endsection