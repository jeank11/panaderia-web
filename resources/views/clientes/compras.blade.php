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
        {{-- FILTROS --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <form method="GET"
              action="{{ route('clientes.compras') }}">

            <div class="row g-3">

                {{-- BUSCAR --}}

                <div class="col-md-5">

                    <label class="form-label fw-bold">

                        🔎 Buscar

                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Producto o número de compra"
                        value="{{ request('buscar') }}">

                </div>


                {{-- TIPO DE PAGO --}}

                <div class="col-md-3">

                    <label class="form-label fw-bold">

                        💳 Tipo de pago

                    </label>

                    <select
                        name="tipo_pago"
                        class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="contado"
                            {{ request('tipo_pago') == 'contado' ? 'selected' : '' }}>

                            💵 Contado

                        </option>

                        <option
                            value="fiado"
                            {{ request('tipo_pago') == 'fiado' ? 'selected' : '' }}>

                            📒 Fiado

                        </option>

                    </select>

                </div>


                {{-- FECHA DESDE --}}

                <div class="col-md-2">

                    <label class="form-label fw-bold">

                        📅 Desde

                    </label>

                    <input
                        type="date"
                        name="fecha_desde"
                        class="form-control"
                        value="{{ request('fecha_desde') }}">

                </div>


                {{-- FECHA HASTA --}}

                <div class="col-md-2">

                    <label class="form-label fw-bold">

                        📅 Hasta

                    </label>

                    <input
                        type="date"
                        name="fecha_hasta"
                        class="form-control"
                        value="{{ request('fecha_hasta') }}">

                </div>

            </div>


            <div class="d-flex gap-2 mt-3">

                <button
                    type="submit"
                    class="btn btn-primary">

                    🔎 Buscar

                </button>


                <a
                    href="{{ route('clientes.compras') }}"
                    class="btn btn-outline-secondary">

                    🧹 Limpiar filtros

                </a>

            </div>

        </form>

    </div>

</div>

    </div>


    @if($ventas->count())


        {{-- COMPRAS --}}

       @foreach($ventas as $venta)

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

 {{-- PAGINACIÓN --}}

    <div class="mt-4">

        <div class="d-flex justify-content-center">

    {{ $ventas->onEachSide(1)->links('pagination::bootstrap-5') }}

</div>

    </div>
       @else

    <div class="card shadow-sm border-0 text-center">

        <div class="card-body py-5">

            <div class="compras-vacio">

                🔎

            </div>


            <h3 class="mt-3">

                No encontramos compras

            </h3>


            <p class="text-muted">

                No hay compras que coincidan con los filtros seleccionados.

            </p>


            <a
                href="{{ route('clientes.compras') }}"
                class="btn btn-outline-primary mt-2">

                🧹 Limpiar filtros

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