@extends('layouts.cliente')

@section('contenido')

<div class="container-fluid px-0">

    {{-- ENCABEZADO --}}

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                📦 Detalle del Pedido

            </h2>

            <p class="text-muted mb-0">

                Información completa de tu pedido.

            </p>

        </div>


        <a
            href="{{ route('clientes.productos') }}"
            class="btn btn-success">

            🛍️ Seguir comprando

        </a>

    </div>


    {{-- ESTADO DEL PEDIDO --}}

    <div class="card shadow-sm border-0 estado-card mb-4">

        <div class="card-body text-center py-4">

            <small class="text-muted d-block mb-2">

                Pedido

            </small>


            <h3 class="fw-bold mb-3">

                📦 {{ $pedido->codigo }}

            </h3>


            @switch($pedido->estado)

                @case('Pendiente')

                    <span class="estado-grande pendiente">

                        🟡 Pendiente

                    </span>

                    <p class="text-muted mt-3 mb-0">

                        Tu pedido fue recibido y está esperando ser preparado.

                    </p>

                @break


                @case('Preparando')

                    <span class="estado-grande preparando">

                        🔵 Preparando

                    </span>

                    <p class="text-muted mt-3 mb-0">

                        Estamos preparando tu pedido.

                    </p>

                @break


                @case('Listo')

                    <span class="estado-grande listo">

                        🟢 Listo para retirar

                    </span>

                    <p class="text-muted mt-3 mb-0">

                        Tu pedido está listo.

                    </p>

                @break


                @case('Entregado')

                    <span class="estado-grande entregado">

                        ✅ Entregado

                    </span>

                    <p class="text-muted mt-3 mb-0">

                        Este pedido ya fue entregado.

                    </p>

                @break


                @case('Cancelado')

                    <span class="estado-grande cancelado">

                        ❌ Cancelado

                    </span>

                    <p class="text-muted mt-3 mb-0">

                        Este pedido fue cancelado.

                    </p>

                @break

            @endswitch

        </div>

    </div>


    {{-- INFORMACIÓN DEL PEDIDO --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-0">

                📋 Información del pedido

            </h5>

        </div>


        <div class="card-body px-4">

            <div class="row g-3">


                <div class="col-md-6">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Fecha del pedido

                        </small>

                        <strong>

                            📅

                            {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}

                        </strong>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Fecha de entrega

                        </small>

                        <strong>

                            📅

                            {{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') }}

                        </strong>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Hora de entrega

                        </small>

                        <strong>

                            ⏰ {{ $pedido->hora_entrega }}

                        </strong>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Tipo de entrega

                        </small>

                        <strong>

                            🚚 {{ $pedido->tipo_entrega }}

                        </strong>

                    </div>

                </div>


                <div class="col-12">

                    <div class="info-box">

                        <small class="text-muted d-block">

                            Dirección

                        </small>

                        <strong>

                            📍 {{ $pedido->direccion_entrega ?? 'Retiro en local' }}

                        </strong>

                    </div>

                </div>


                @if($pedido->observaciones)

                    <div class="col-12">

                        <div class="info-box">

                            <small class="text-muted d-block">

                                Observaciones

                            </small>

                            <strong>

                                📝 {{ $pedido->observaciones }}

                            </strong>

                        </div>

                    </div>

                @endif


            </div>

        </div>

    </div>


    {{-- PRODUCTOS --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-0">

                🛒 Productos del pedido

            </h5>

        </div>


        <div class="card-body px-4">


            @forelse($pedido->detalles as $detalle)


                {{-- PRODUCTO --}}

                <div class="producto-pedido">

                    <div>

                        <h6 class="fw-bold mb-1">

                            {{ $detalle->producto->nombre ?? 'Producto eliminado' }}

                        </h6>

                        <small class="text-muted">

                            {{ $detalle->cantidad }} ×

                            ${{ number_format($detalle->precio,2) }}

                        </small>

                    </div>


                    <strong class="text-success">

                        ${{ number_format($detalle->subtotal,2) }}

                    </strong>

                </div>


            @empty


                <div class="alert alert-info text-center mb-0">

                    No hay productos registrados.

                </div>


            @endforelse


            <hr>


            {{-- TOTAL --}}

            <div class="d-flex justify-content-between align-items-center">

                <span class="fs-5 fw-bold">

                    Total del pedido

                </span>


                <strong class="total-pedido">

                    ${{ number_format($pedido->total,2) }}

                </strong>

            </div>


        </div>

    </div>


    {{-- BOTONES --}}

    <div class="d-flex flex-column flex-md-row gap-2 mb-4">

        <a
            href="{{ route('clientes.pedidos') }}"
            class="btn btn-outline-secondary">

            ← Volver a mis pedidos

        </a>


        <a
            href="{{ route('clientes.productos') }}"
            class="btn btn-success">

            🛍️ Hacer otro pedido

        </a>

    </div>

</div>


<style>

    .estado-card{

        border-radius:18px;

        overflow:hidden;

    }


    .estado-grande{

        display:inline-block;

        padding:10px 20px;

        border-radius:30px;

        font-size:18px;

        font-weight:bold;

    }


    .pendiente{

        background:#fff3cd;

        color:#664d03;

    }


    .preparando{

        background:#cfe2ff;

        color:#084298;

    }


    .listo{

        background:#d1e7dd;

        color:#0f5132;

    }


    .entregado{

        background:#d1e7dd;

        color:#0f5132;

    }


    .cancelado{

        background:#f8d7da;

        color:#842029;

    }


    .info-box{

        background:#f8f9fa;

        border-radius:12px;

        padding:15px;

        height:100%;

    }


    .producto-pedido{

        display:flex;

        justify-content:space-between;

        align-items:center;

        gap:15px;

        padding:15px 5px;

        border-bottom:1px solid #eeeeee;

    }


    .producto-pedido:last-of-type{

        border-bottom:none;

    }


    .total-pedido{

        font-size:28px;

        color:#198754;

    }


    @media(max-width:576px){

        .estado-grande{

            font-size:16px;

        }


        .producto-pedido{

            padding:14px 0;

        }


        .total-pedido{

            font-size:24px;

        }

    }

</style>

@endsection