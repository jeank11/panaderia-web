@extends('layouts.cliente')

@section('contenido')

<div class="container">


    <h2 class="mb-4">

        📦 Detalle del Pedido

    </h2>




    <div class="card shadow mb-4">


        <div class="card-header bg-primary text-white">


            <h5 class="mb-0">

                Pedido {{ $pedido->codigo }}

            </h5>


        </div>




        <div class="card-body">


            <div class="row">



                <div class="col-md-6">


                    <p>

                        <strong>
                            Fecha del pedido:
                        </strong>

                        {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}

                    </p>



                    <p>

                        <strong>
                            Fecha de entrega:
                        </strong>

                        {{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') }}

                    </p>



                    <p>

                        <strong>
                            Hora:
                        </strong>

                        {{ $pedido->hora_entrega }}

                    </p>


                </div>




                <div class="col-md-6">


                    <p>

                        <strong>
                            Tipo de entrega:
                        </strong>

                        {{ $pedido->tipo_entrega }}

                    </p>



                    <p>

                        <strong>
                            Dirección:
                        </strong>

                        {{ $pedido->direccion_entrega ?? 'Retiro en local' }}

                    </p>




                    <p>

                        <strong>
                            Estado:
                        </strong>


                        @switch($pedido->estado)


                            @case('Pendiente')

                                <span class="badge bg-warning text-dark">

                                    🟡 Pendiente

                                </span>

                            @break



                            @case('Preparando')

                                <span class="badge bg-primary">

                                    🔵 Preparando

                                </span>

                            @break



                            @case('Listo')

                                <span class="badge bg-success">

                                    🟢 Listo para retirar

                                </span>

                            @break



                            @case('Entregado')

                                <span class="badge bg-success">

                                    ✅ Entregado

                                </span>

                            @break



                            @case('Cancelado')

                                <span class="badge bg-danger">

                                    ❌ Cancelado

                                </span>

                            @break



                        @endswitch


                    </p>



                </div>


            </div>




            @if($pedido->observaciones)


            <hr>


            <p>

                <strong>
                    Observaciones:
                </strong>

                {{ $pedido->observaciones }}

            </p>


            @endif



        </div>


    </div>





    <div class="card shadow">


        <div class="card-header">


            <strong>

                🛒 Productos del pedido

            </strong>


        </div>





        <div class="card-body">



            <table class="table table-bordered align-middle">


                <thead class="table-light">


                    <tr>

                        <th>
                            Producto
                        </th>

                        <th width="120">
                            Cantidad
                        </th>

                        <th width="140">
                            Precio
                        </th>

                        <th width="140">
                            Subtotal
                        </th>


                    </tr>


                </thead>




                <tbody>



                @forelse($pedido->detalles as $detalle)



                <tr>


                    <td>


                        {{ $detalle->producto->nombre ?? 'Producto eliminado' }}


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



                @empty



                <tr>

                    <td colspan="4" class="text-center">


                        No hay productos registrados.


                    </td>


                </tr>



                @endforelse



                </tbody>


            </table>




            <div class="text-end">


                <h4>


                    Total:

                    <span class="text-success">

                        ${{ number_format($pedido->total,2) }}

                    </span>


                </h4>


            </div>



        </div>


    </div>





    <div class="mt-4">


        <a href="{{ route('clientes.pedidos') }}"
   class="btn btn-secondary">
    ← Volver a mis pedidos
</a>



    </div>



</div>


@endsection