@extends('layouts.cliente')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-md-8">

        {{-- Encabezado --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                <h3 class="mb-0">
                    💰 Detalle del pago
                </h3>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <small class="text-muted">
                            Fecha del pago
                        </small>

                        <h5>
                            {{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}
                        </h5>

                    </div>


                    <div class="col-md-6 mb-3">

                        <small class="text-muted">
                            Monto pagado
                        </small>

                        <h4 class="text-success">

                            ${{ number_format($pago->monto, 2) }}

                        </h4>

                    </div>

                </div>


                <hr>


                <div class="mb-2">

                    <strong>
                        Observación:
                    </strong>

                    <div class="mt-1">

                        {{ $pago->observacion ?? 'Sin observación' }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Ventas asociadas --}}

        @if($pago->ventas->count() > 0)

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-light">

                    <h4 class="mb-0">
                        🧾 Compras canceladas con este pago
                    </h4>

                </div>


                <div class="card-body">


                    @foreach($pago->ventas as $venta)

                        <div class="card border mb-3">

                            <div class="card-header">

                                <div class="d-flex justify-content-between align-items-center">

                                    <strong>
                                        🧾 Venta #{{ $venta->id }}
                                    </strong>

                                    <span class="badge bg-success">
                                        Pagada
                                    </span>

                                </div>

                            </div>


                            <div class="card-body">


                                <div class="row mb-3">

                                    <div class="col-md-6">

                                        <strong>
                                            Fecha de compra:
                                        </strong>

                                        <br>

                                        {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}

                                    </div>


                                    <div class="col-md-6">

                                        <strong>
                                            Monto cancelado:
                                        </strong>

                                        <br>

                                        <span class="text-success">

                                            ${{ number_format(
                                                $venta->pivot->monto_aplicado,
                                                2
                                            ) }}

                                        </span>

                                    </div>

                                </div>


                                <hr>


                                <h6 class="mb-3">
                                    🛒 Productos de la compra
                                </h6>


                                <div class="table-responsive">

                                    <table class="table table-sm table-bordered">

                                        <thead class="table-light">

                                            <tr>

                                                <th>
                                                    Producto
                                                </th>

                                                <th class="text-center">
                                                    Cantidad
                                                </th>

                                                <th class="text-end">
                                                    Precio
                                                </th>

                                                <th class="text-end">
                                                    Subtotal
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

                                                        {{ $detalle->cantidad }}

                                                    </td>


                                                    <td class="text-end">

                                                        ${{ number_format(
                                                            $detalle->precio,
                                                            2
                                                        ) }}

                                                    </td>


                                                    <td class="text-end">

                                                        ${{ number_format(
                                                            $detalle->subtotal,
                                                            2
                                                        ) }}

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>


                                <div class="text-end">

                                    <strong>
                                        Total de la venta:
                                    </strong>

                                    <span class="ms-2">

                                        ${{ number_format(
                                            $venta->total,
                                            2
                                        ) }}

                                    </span>

                                </div>


                            </div>

                        </div>

                    @endforeach


                    {{-- Total del pago --}}

                    <div class="alert alert-success mb-0">

                        <div class="d-flex justify-content-between align-items-center">

                            <strong>
                                💰 Total aplicado:
                            </strong>

                            <strong class="fs-5">

                                ${{ number_format(
                                    $pago->monto,
                                    2
                                ) }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


        @elseif($pago->venta)

            {{-- Pago individual --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-light">

                    <h4 class="mb-0">
                        🧾 Compra asociada
                    </h4>

                </div>


                <div class="card-body">


                    <div class="row mb-3">

                        <div class="col-md-6">

                            <strong>
                                Venta:
                            </strong>

                            #{{ $pago->venta->id }}

                        </div>


                        <div class="col-md-6">

                            <strong>
                                Fecha:
                            </strong>

                            {{ \Carbon\Carbon::parse(
                                $pago->venta->fecha
                            )->format('d/m/Y') }}

                        </div>

                    </div>


                    <hr>


                    <h6>
                        🛒 Productos
                    </h6>


                    <div class="table-responsive">

                        <table class="table table-sm table-bordered">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Producto
                                    </th>

                                    <th class="text-center">
                                        Cantidad
                                    </th>

                                    <th class="text-end">
                                        Precio
                                    </th>

                                    <th class="text-end">
                                        Subtotal
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($pago->venta->detalles as $detalle)

                                    <tr>

                                        <td>
                                            {{ $detalle->producto->nombre }}
                                        </td>

                                        <td class="text-center">
                                            {{ $detalle->cantidad }}
                                        </td>

                                        <td class="text-end">
                                            ${{ number_format(
                                                $detalle->precio,
                                                2
                                            ) }}
                                        </td>

                                        <td class="text-end">
                                            ${{ number_format(
                                                $detalle->subtotal,
                                                2
                                            ) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    <div class="text-end">

                        <strong>
                            Total de la venta:
                        </strong>

                        ${{ number_format(
                            $pago->venta->total,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        @else

            <div class="alert alert-info">

                ℹ️ Este pago no tiene compras asociadas.

            </div>

        @endif


        {{-- Volver --}}

        <div class="text-end">

            <a
                href="{{ route('clientes.estado_cuenta') }}"
                class="btn btn-secondary">

                ← Volver al estado de cuenta

            </a>

        </div>

    </div>

</div>

@endsection