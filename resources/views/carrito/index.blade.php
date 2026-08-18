@extends(session()->has('cliente_id') ? 'layouts.cliente' : 'layouts.web')

@section('titulo', 'Mi Carrito')

@section('contenido')

<div class="container py-4">

    {{-- ENCABEZADO --}}

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                🛒 Mi Carrito
            </h2>

            <p class="text-muted mb-0">
                Revisá tus productos antes de confirmar el pedido.
            </p>

        </div>

        <a
            href="{{ route('clientes.productos') }}"
            class="btn btn-warning">

            ← Seguir comprando

        </a>

    </div>


    {{-- MENSAJES --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(count($carrito))

        @php

            $total = 0;

        @endphp


        {{-- CARRITO --}}

        <div class="card shadow-sm border-0 carrito-card">

            <div class="card-body p-0">

                {{-- TABLA PARA COMPUTADORA --}}

                <div class="table-responsive d-none d-md-block">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>Producto</th>

                                <th class="text-center">
                                    Precio
                                </th>

                                <th class="text-center">
                                    Cantidad
                                </th>

                                <th class="text-center">
                                    Subtotal
                                </th>

                                <th class="text-center">
                                    Acción
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($carrito as $item)

                            @php

                                $subtotal =
                                    $item['precio'] *
                                    $item['cantidad'];

                                $total += $subtotal;

                            @endphp

                            <tr>

                                {{-- PRODUCTO --}}

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        @if(!empty($item['imagen']))

                                            <img
                                                src="{{ asset('storage/'.$item['imagen']) }}"
                                                class="imagen-carrito"
                                                alt="{{ $item['nombre'] }}">

                                        @else

                                            <div class="imagen-carrito sin-imagen">

                                                🥖

                                            </div>

                                        @endif


                                        <div>

                                            <strong>

                                                {{ $item['nombre'] }}

                                            </strong>

                                        </div>

                                    </div>

                                </td>


                                {{-- PRECIO --}}

                                <td class="text-center">

                                    ${{ number_format($item['precio'],2) }}

                                </td>


                                {{-- CANTIDAD --}}

                                <td class="text-center">

                                    <div class="d-flex justify-content-center align-items-center gap-2">

                                        <form
                                            action="{{ route('carrito.disminuir',$item['id']) }}"
                                            method="POST">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-outline-secondary btn-sm">

                                                ➖

                                            </button>

                                        </form>


                                        <strong class="cantidad">

                                            {{ $item['cantidad'] }}

                                        </strong>


                                        <form
                                            action="{{ route('carrito.aumentar',$item['id']) }}"
                                            method="POST">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-outline-success btn-sm">

                                                ➕

                                            </button>

                                        </form>

                                    </div>

                                </td>


                                {{-- SUBTOTAL --}}

                                <td class="text-center">

                                    <strong class="text-success">

                                        ${{ number_format($subtotal,2) }}

                                    </strong>

                                </td>


                                {{-- ELIMINAR --}}

                                <td class="text-center">

                                    <form
                                        action="{{ route('carrito.eliminar',$item['id']) }}"
                                        method="POST">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm"
                                            title="Eliminar producto">

                                            🗑

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- TARJETAS PARA CELULAR --}}

                <div class="d-md-none p-3">

                    @foreach($carrito as $item)

                        @php

                            $subtotal =
                                $item['precio'] *
                                $item['cantidad'];

                        @endphp

                        <div class="producto-carrito-mobile mb-3">

                            <div class="d-flex gap-3">

                                @if(!empty($item['imagen']))

                                    <img
                                        src="{{ asset('storage/'.$item['imagen']) }}"
                                        class="imagen-mobile"
                                        alt="{{ $item['nombre'] }}">

                                @else

                                    <div class="imagen-mobile sin-imagen">

                                        🥖

                                    </div>

                                @endif


                                <div class="flex-grow-1">

                                    <div class="d-flex justify-content-between gap-2">

                                        <h5 class="fw-bold mb-1">

                                            {{ $item['nombre'] }}

                                        </h5>


                                        <form
                                            action="{{ route('carrito.eliminar',$item['id']) }}"
                                            method="POST">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger btn-sm">

                                                🗑

                                            </button>

                                        </form>

                                    </div>


                                    <p class="text-muted mb-2">

                                        ${{ number_format($item['precio'],2) }}

                                        cada uno

                                    </p>


                                    <div class="d-flex justify-content-between align-items-center">

                                        <div class="d-flex align-items-center gap-2">

                                            <form
                                                action="{{ route('carrito.disminuir',$item['id']) }}"
                                                method="POST">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-secondary btn-sm">

                                                    ➖

                                                </button>

                                            </form>


                                            <strong>

                                                {{ $item['cantidad'] }}

                                            </strong>


                                            <form
                                                action="{{ route('carrito.aumentar',$item['id']) }}"
                                                method="POST">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-success btn-sm">

                                                    ➕

                                                </button>

                                            </form>

                                        </div>


                                        <strong class="text-success">

                                            ${{ number_format($subtotal,2) }}

                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- RESUMEN --}}

        <div class="row mt-4">

            <div class="col-md-6 mb-3">

                <form
                    action="{{ route('carrito.vaciar') }}"
                    method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-danger">

                        🧹 Vaciar carrito

                    </button>

                </form>

            </div>


            <div class="col-md-6">

                <div class="card shadow-sm border-0 resumen-card">

                    <div class="card-body">

                        <h5 class="text-muted">
                            Resumen de compra
                        </h5>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Productos
                            </span>

                            <strong>
                                {{ count($carrito) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between align-items-center">

                            <span class="fs-5">
                                Total
                            </span>

                            <strong class="total">

                                ${{ number_format($total,2) }}

                            </strong>

                        </div>


                        <a
                            href="{{ route('pedido.confirmar') }}"
                            class="btn btn-success btn-lg w-100 mt-3">

                            ✅ Confirmar Pedido

                        </a>

                    </div>

                </div>

            </div>

        </div>


    @else

        {{-- CARRITO VACÍO --}}

        <div class="card shadow-sm border-0 text-center">

            <div class="card-body py-5">

                <div class="carrito-vacio">

                    🛒

                </div>

                <h3 class="mt-3">

                    Tu carrito está vacío

                </h3>

                <p class="text-muted">

                    Todavía no agregaste productos.

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

    .carrito-card{

        border-radius:16px;

        overflow:hidden;

    }


    .imagen-carrito{

        width:65px;

        height:65px;

        object-fit:cover;

        border-radius:10px;

    }


    .sin-imagen{

        display:flex;

        justify-content:center;

        align-items:center;

        background:#f5f5f5;

        font-size:32px;

    }


    .cantidad{

        min-width:25px;

        text-align:center;

    }


    .producto-carrito-mobile{

        padding:15px;

        border:1px solid #e5e5e5;

        border-radius:12px;

        background:white;

    }


    .imagen-mobile{

        width:85px;

        height:85px;

        object-fit:cover;

        border-radius:10px;

        flex-shrink:0;

    }


    .resumen-card{

        border-radius:16px;

    }


    .total{

        font-size:28px;

        color:#198754;

    }


    .carrito-vacio{

        font-size:80px;

    }


    @media(max-width:576px){

        .imagen-mobile{

            width:75px;

            height:75px;

        }


        .total{

            font-size:25px;

        }

    }

</style>

@endsection