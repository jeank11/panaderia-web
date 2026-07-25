@extends('layouts.cliente')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h3 class="mb-0">
                    🧾 Detalle de Compra #{{ $venta->id }}
                </h3>

            </div>


            <div class="card-body">


                <p>
                    <strong>Fecha:</strong>

                    {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}

                </p>


                <p>

                    <strong>Estado:</strong>

                    @if($venta->estado)

                        <span class="badge bg-success">
                            Activa
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Anulada
                        </span>

                    @endif

                </p>


                <hr>


                <h5>
                    Productos comprados
                </h5>


                <table class="table table-bordered">


                    <thead class="table-dark">

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th>
                                Cantidad
                            </th>

                            <th>
                                Precio
                            </th>

                            <th>
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


                            <td>
                                {{ $detalle->cantidad }}
                            </td>


                            <td>

                                ${{ number_format($detalle->precio,2) }}

                            </td>


                            <td>

                                ${{ number_format($detalle->subtotal,2) }}

                            </td>


                        </tr>


                    @endforeach


                    </tbody>


                </table>


                <hr>


                <h4 class="text-end">

                    Total:

                    ${{ number_format($venta->total,2) }}

                </h4>


            </div>


            <div class="card-footer text-end">


                <a href="{{ route('clientes.compras') }}"
                   class="btn btn-secondary">

                    ← Volver

                </a>


            </div>


        </div>

    </div>

</div>


@endsection