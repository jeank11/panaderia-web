@extends('layouts.cliente')

@section('contenido')

<div class="container-fluid px-0">

    {{-- ENCABEZADO --}}

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                📦 Mis Pedidos

            </h2>

            <p class="text-muted mb-0">

                Aquí puedes consultar tus pedidos y conocer su estado.

            </p>

        </div>


        <a
            href="{{ route('clientes.productos') }}"
            class="btn btn-success">

            🛍️ Hacer un nuevo pedido

        </a>

    </div>


    @if($pedidos->count())


        {{-- VISTA COMPUTADORA --}}

        <div class="card shadow-sm border-0 pedidos-card d-none d-md-block">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th class="ps-4">
                                    Código
                                </th>

                                <th>
                                    Fecha
                                </th>

                                <th>
                                    Hora
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th class="text-center">
                                    Acción
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($pedidos as $pedido)

                            <tr>

                                <td class="ps-4">

                                    <strong>

                                        📦 {{ $pedido->codigo }}

                                    </strong>

                                </td>


                                <td>

                                    {{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') }}

                                </td>


                                <td>

                                    ⏰ {{ $pedido->hora_entrega }}

                                </td>


                                <td>

                                    <strong class="text-success">

                                        ${{ number_format($pedido->total,2) }}

                                    </strong>

                                </td>


                                <td>

                                    @if($pedido->estado == 'Pendiente')

                                        <span class="badge bg-warning text-dark estado-badge">

                                            🟡 Pendiente

                                        </span>

                                    @elseif($pedido->estado == 'Preparando')

                                        <span class="badge bg-primary estado-badge">

                                            🔵 Preparando

                                        </span>

                                    @elseif($pedido->estado == 'Listo')

                                        <span class="badge bg-success estado-badge">

                                            🟢 Listo

                                        </span>

                                    @elseif($pedido->estado == 'Entregado')

                                        <span class="badge bg-success estado-badge">

                                            ✅ Entregado

                                        </span>

                                    @elseif($pedido->estado == 'Cancelado')

                                        <span class="badge bg-danger estado-badge">

                                            ❌ Cancelado

                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route('clientes.pedido.detalle',$pedido) }}"
                                        class="btn btn-outline-primary btn-sm">

                                        👁️ Ver pedido

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- VISTA CELULAR --}}

        <div class="d-md-none">

            @foreach($pedidos as $pedido)

                <div class="card shadow-sm border-0 pedido-mobile mb-3">

                    <div class="card-body">


                        {{-- CABECERA --}}

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <h5 class="fw-bold mb-1">

                                    📦 {{ $pedido->codigo }}

                                </h5>

                                <small class="text-muted">

                                    Pedido realizado

                                </small>

                            </div>


                            @if($pedido->estado == 'Pendiente')

                                <span class="badge bg-warning text-dark">

                                    🟡 Pendiente

                                </span>

                            @elseif($pedido->estado == 'Preparando')

                                <span class="badge bg-primary">

                                    🔵 Preparando

                                </span>

                            @elseif($pedido->estado == 'Listo')

                                <span class="badge bg-success">

                                    🟢 Listo

                                </span>

                            @elseif($pedido->estado == 'Entregado')

                                <span class="badge bg-success">

                                    ✅ Entregado

                                </span>

                            @elseif($pedido->estado == 'Cancelado')

                                <span class="badge bg-danger">

                                    ❌ Cancelado

                                </span>

                            @endif

                        </div>


                        <hr>


                        {{-- INFORMACIÓN --}}

                        <div class="row text-center">

                            <div class="col-4">

                                <small class="text-muted d-block">

                                    Fecha

                                </small>

                                <strong>

                                    {{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') }}

                                </strong>

                            </div>


                            <div class="col-4">

                                <small class="text-muted d-block">

                                    Hora

                                </small>

                                <strong>

                                    {{ $pedido->hora_entrega }}

                                </strong>

                            </div>


                            <div class="col-4">

                                <small class="text-muted d-block">

                                    Total

                                </small>

                                <strong class="text-success">

                                    ${{ number_format($pedido->total,2) }}

                                </strong>

                            </div>

                        </div>


                        <div class="d-grid mt-4">

                            <a
                                href="{{ route('clientes.pedido.detalle',$pedido) }}"
                                class="btn btn-outline-primary">

                                👁️ Ver pedido

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- PAGINACIÓN --}}

        <div class="mt-4">

            {{ $pedidos->links() }}

        </div>


    @else


        {{-- SIN PEDIDOS --}}

        <div class="card shadow-sm border-0 text-center">

            <div class="card-body py-5">

                <div class="pedido-vacio">

                    📦

                </div>


                <h3 class="mt-3">

                    Todavía no tienes pedidos

                </h3>


                <p class="text-muted">

                    Cuando realices un pedido, podrás consultar aquí su estado y sus detalles.

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

    .pedidos-card{

        border-radius:16px;

        overflow:hidden;

    }


    .estado-badge{

        font-size:13px;

        padding:7px 10px;

    }


    .pedido-mobile{

        border-radius:16px;

    }


    .pedido-vacio{

        font-size:80px;

    }


    @media(max-width:576px){

        .pedido-mobile{

            margin-left:0;

            margin-right:0;

        }

    }

</style>

@endsection