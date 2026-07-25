@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Detalle de Venta #{{ $venta->id }}
</h2>


<div class="card mb-4">

    <div class="card-header">
        Información de la venta
    </div>


    <div class="card-body">


        <div class="row">

            <div class="col-md-4">

                <strong>Cliente:</strong>

                <br>

                {{ $venta->cliente->nombre_completo }}

            </div>


            <div class="col-md-4">

                <strong>Usuario:</strong>

                <br>

                {{ $venta->usuario->name }}

            </div>


            <div class="col-md-4">

                <strong>Fecha:</strong>

                <br>

                {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}

            </div>

        </div>


    </div>

</div>



<div class="card">

    <div class="card-header">
        Productos vendidos
    </div>


    <div class="card-body">


        <table class="table table-bordered">


            <thead class="table-dark">

                <tr>

                    <th>Producto</th>

                    <th>Cantidad</th>

                    <th>Precio</th>

                    <th>Subtotal</th>

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


        <div class="text-end">

            <h3>

                Total:

                ${{ number_format($venta->total,2) }}

            </h3>

        </div>


    </div>

</div>


<div class="mt-3">

<a href="{{ route('ventas.index') }}"
   class="btn btn-secondary">

    Volver

</a>
<a href="{{ route('ventas.ticket',$venta) }}"
   target="_blank"
   class="btn btn-dark">

    🧾 Imprimir Ticket

</a>

</div>


@endsection