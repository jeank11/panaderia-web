@extends('layouts.cliente')

@section('contenido')

<div class="container-fluid px-0">

    {{-- ENCABEZADO --}}

    <div class="mb-4">

        <h2 class="fw-bold mb-1">

            📜 Mis Compras

        </h2>

        <p class="text-muted mb-0">

            Aquí puedes consultar el historial de tus compras.

        </p>

    </div>


    @if($cliente->ventas->count())


        {{-- COMPRAS --}}

        @foreach($cliente->ventas as $venta)

            <div class="card shadow-sm border-0 compra-card mb-4">

                {{-- CABECERA --}}

                <div class="card-header compra-header">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                        <div>

                            <h5 class="mb-1 fw-bold">

                                🧾 Compra #{{ $venta->id }}

                            </h5>

                            <small>

                                📅

                                {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}

                            </small>

                        </div>


                        <div>

                            @if($venta->pedido)

                                <span class="badge bg-light text-dark">

                                    📦 {{ $venta->pedido->codigo }}

                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- CUERPO --}}

                <div class="card-body">


                    {{-- PRODUCTOS --}}

                    <h5 class="fw-bold mb-3">

                        🛍️ Productos

                    </h5>


                    <div class="productos-compra">


                        @foreach($venta->detalles as $detalle)

                            <div class="producto-compra">

                                <div>

                                    <strong>

                                        {{ $detalle->producto->nombre }}

                                    </strong>

                                    <div class="text-muted small">

                                        Cantidad: {{ $detalle->cantidad }}

                                    </div>

                                </div>


                                <strong class="text-success">

                                    ${{ number_format($detalle->subtotal,2) }}

                                </strong>

                            </div>

                        @endforeach


                    </div>


                    <hr>


                    {{-- INFORMACIÓN DE PAGO --}}

                    <div class="row g-3 mb-3">


                        <div class="col-md-4">

                            <div class="info-compra">

                                <small class="text-muted d-block">

                                    Tipo de pago

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

                            <div class="info-compra">

                                <small class="text-muted d-block">

                                    Estado del pago

                                </small>


                                @if($venta->estado_pago == 'pagada')

                                    <strong class="text-success">

                                        ✅ Pagada

                                    </strong>

                                @elseif($venta->estado_pago == 'parcial')

                                    <strong class="text-warning">

                                        🟡 Pago parcial

                                    </strong>

                                @else

                                    <strong class="text-danger">

                                        🔴 Pendiente

                                    </strong>

                                @endif

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="info-compra">

                                <small class="text-muted d-block">

                                    Total

                                </small>


                                <strong class="total-compra">

                                    ${{ number_format($venta->total,2) }}

                                </strong>

                            </div>

                        </div>


                    </div>


                    {{-- BOTÓN DETALLE --}}

                    <div class="d-grid d-md-flex justify-content-md-end">

                        <a
                            href="{{ route('clientes.detalle.compra',$venta) }}"
                            class="btn btn-outline-primary">

                            👁️ Ver detalle de compra

                        </a>

                    </div>


                </div>

            </div>

        @endforeach


    @else


        {{-- SIN COMPRAS --}}

        <div class="card shadow-sm border-0 text-center">

            <div class="card-body py-5">

                <div class="compras-vacio">

                    📜

                </div>


                <h3 class="mt-3">

                    Todavía no tienes compras

                </h3>


                <p class="text-muted">

                    Cuando realices una compra, aparecerá aquí tu historial.

                </p>


                <a
                    href="{{ route('clientes.productos') }}"
                    class="btn btn-success btn-lg mt-2">

                    🛍️ Comenzar a comprar

                </a>

            </div>

        </div>

    @endif

</div>


<style>

    .compra-card{

        border-radius:16px;

        overflow:hidden;

    }


    .compra-header{

        background:#198754;

        color:white;

        padding:18px 20px;

    }


    .productos-compra{

        border:1px solid #e5e5e5;

        border-radius:12px;

        overflow:hidden;

    }


    .producto-compra{

        display:flex;

        justify-content:space-between;

        align-items:center;

        padding:14px 16px;

        border-bottom:1px solid #eeeeee;

    }


    .producto-compra:last-child{

        border-bottom:none;

    }


    .info-compra{

        background:#f8f9fa;

        border-radius:10px;

        padding:14px;

        height:100%;

    }


    .total-compra{

        font-size:22px;

        color:#198754;

    }


    .compras-vacio{

        font-size:80px;

    }


    @media(max-width:576px){

        .producto-compra{

            padding:12px;

        }


        .compra-header{

            padding:15px;

        }

    }

</style>

@endsection