@extends('layouts.cliente')

@section('contenido')


<h2 class="mb-4">

    📜 Mis Compras

</h2>



@if($cliente->ventas->count())


@foreach($cliente->ventas as $venta)



<div class="card shadow mb-4">


    <div class="card-header bg-success text-white">


        <h5 class="mb-0">

            Compra #{{ $venta->id }}

        </h5>


    </div>




    <div class="card-body">


        <p>

            <strong>
                Fecha:
            </strong>

            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}

        </p>



        @if($venta->pedido)


        <p>

            <strong>
                Pedido:
            </strong>

            {{ $venta->pedido->codigo }}

        </p>


        @endif




        <hr>




        <h5>

            Productos

        </h5>



        <ul class="list-group mb-3">



        @foreach($venta->detalles as $detalle)



            <li class="list-group-item d-flex justify-content-between">


                <span>


                    {{ $detalle->producto->nombre }}

                    x{{ $detalle->cantidad }}


                </span>



                <strong>


                    ${{ number_format($detalle->subtotal,2) }}


                </strong>



            </li>



        @endforeach



        </ul>




        <div class="text-end">


            <h4>


                Total:

                <span class="text-success">

                    ${{ number_format($venta->total,2) }}

                </span>


            </h4>


        </div>



    </div>


</div>



@endforeach



@else



<div class="alert alert-info text-center">

    Todavía no tienes compras realizadas.

</div>



@endif



@endsection