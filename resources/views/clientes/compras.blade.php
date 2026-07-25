@extends('layouts.cliente')

@section('contenido')


<h2 class="mb-4">
    🛒 Mis Compras
</h2>


@forelse($cliente->ventas as $venta)


<div class="card shadow mb-4">


    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            Compra #{{ $venta->id }}

        </h5>

    </div>


    <div class="card-body">


        <p>

            <strong>Fecha:</strong>

            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}

        </p>


        <p>

            <strong>Total:</strong>

            ${{ number_format($venta->total,2) }}

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

            Productos

        </h5>


        <ul class="list-group mb-3">


            @foreach($venta->detalles as $detalle)


                <li class="list-group-item d-flex justify-content-between align-items-center">


                    <div>

                        <strong>

                            {{ $detalle->producto->nombre }}

                        </strong>


                        <br>


                        Cantidad:

                        {{ $detalle->cantidad }}


                    </div>



                    <span>

                        ${{ number_format($detalle->subtotal,2) }}

                    </span>


                </li>


            @endforeach


        </ul>



        <div class="text-end">


            <a href="{{ route('clientes.detalle.compra',$venta->id) }}"

               class="btn btn-primary">


                <i class="bi bi-eye"></i>

                Ver detalle


            </a>


        </div>



    </div>


</div>


@empty


<div class="alert alert-info text-center">

    Todavía no tienes compras registradas.

</div>


@endforelse



@endsection