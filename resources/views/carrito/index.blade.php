@extends('layouts.web')

@section('titulo', 'Mi Carrito')

@section('contenido')

<div class="container py-5">

    <h2 class="mb-4">

        🛒 Mi Carrito

    </h2>

    @if(count($carrito))

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Producto</th>

                        <th class="text-center">Precio</th>

                        <th class="text-center" width="220">

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

                @php

                    $total = 0;

                @endphp

                @foreach($carrito as $item)

                    @php

                        $subtotal = $item['precio'] * $item['cantidad'];

                        $total += $subtotal;

                    @endphp

                    <tr>

                        <td>

                            {{ $item['nombre'] }}

                        </td>

                        <td class="text-center">

                            ${{ number_format($item['precio'],2) }}

                        </td>

                        <td class="text-center">

                            <div class="d-flex justify-content-center align-items-center gap-2">

                                <form
                                    action="{{ route('carrito.disminuir',$item['id']) }}"
                                    method="POST">

                                    @csrf

                                    <button
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
                                        class="btn btn-outline-success btn-sm">

                                        ➕

                                    </button>

                                </form>

                            </div>

                        </td>

                        <td class="text-center">

                            ${{ number_format($subtotal,2) }}

                        </td>

                        <td class="text-center">

                            <form
                                action="{{ route('carrito.eliminar',$item['id']) }}"
                                method="POST">

                                @csrf

                                <button
                                    class="btn btn-danger btn-sm">

                                    🗑

                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            <hr>

            <div class="row align-items-center">

                <div class="col-md-6">

                    <form
                        action="{{ route('carrito.vaciar') }}"
                        method="POST">

                        @csrf

                        <button
                            class="btn btn-outline-danger">

                            🧹 Vaciar carrito

                        </button>

                    </form>

                </div>

                <div class="col-md-6 text-end">

                    <h3>

                        Total:

                        <span class="text-success">

                            ${{ number_format($total,2) }}

                        </span>

                    </h3>

                    <a
                        href="#"
                        class="btn btn-success btn-lg mt-2">

                        ✅ Confirmar Pedido

                    </a>

                </div>

            </div>

        </div>

    </div>

    @else

        <div class="alert alert-info">

            Tu carrito está vacío.

        </div>

    @endif

</div>

@endsection