@extends('layouts.app')

@section('contenido')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            📦 Detalle del Pedido {{ $pedido->codigo }}
        </h2>

        <a href="{{ route('pedidos.index') }}"
           class="btn btn-secondary">

            Volver

        </a>

    </div>


    <div class="row">


        <div class="col-md-6">


            <div class="card shadow mb-4">


                <div class="card-header bg-primary text-white">

                    Datos del cliente

                </div>


                <div class="card-body">


                    <p>
                        <strong>Nombre:</strong>

                        {{ $pedido->cliente->nombre_completo }}

                    </p>


                    <p>
                        <strong>Email:</strong>

                        {{ $pedido->cliente->email }}

                    </p>


                    <p>
                        <strong>Teléfono:</strong>

                        {{ $pedido->cliente->telefono }}

                    </p>


                </div>


            </div>


        </div>




        <div class="col-md-6">


            <div class="card shadow mb-4">


                <div class="card-header bg-warning">

                    Datos de entrega

                </div>


                <div class="card-body">


                    <p>

                        <strong>Fecha:</strong>

                        {{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') }}

                    </p>


                    <p>

                        <strong>Hora:</strong>

                        {{ $pedido->hora_entrega }}

                    </p>


                    <p>

                        <strong>Tipo:</strong>

                        {{ $pedido->tipo_entrega }}

                    </p>


                    @if($pedido->direccion_entrega)

                    <p>

                        <strong>Dirección:</strong>

                        {{ $pedido->direccion_entrega }}

                    </p>

                    @endif



                    <p>

                        <strong>Estado:</strong>


                        <span class="badge bg-warning text-dark">

                            {{ $pedido->estado }}

                        </span>


                    </p>


                </div>


            </div>


        </div>


    </div>





    <div class="card shadow">


        <div class="card-header bg-dark text-white">

            Productos

        </div>


        <div class="card-body">


            <table class="table table-bordered">


                <thead>

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


                @foreach($pedido->detalles as $detalle)


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

                    <span class="text-success">

                        ${{ number_format($pedido->total,2) }}

                    </span>

                </h3>


            </div>


        </div>


    </div>



</div>


@endsection