@extends('layouts.web')

@section('titulo', $producto->nombre)

@section('contenido')

<div class="container py-5">

    <div class="row">

        <div class="col-md-6">

            @if($producto->imagen)

                <img
                src="{{ asset('storage/'.$producto->imagen) }}"
                class="img-fluid rounded shadow">

            @else

                <div class="alert alert-secondary">
                    Sin imagen disponible
                </div>

            @endif

        </div>


        <div class="col-md-6">

            <h1>
                {{ $producto->nombre }}
            </h1>


            <p>
                Código:
                {{ $producto->codigo }}
            </p>


            <h3 class="text-success">

                ${{ number_format($producto->precio_venta,2) }}

            </h3>


            <p>
                Stock disponible:
                {{ $producto->stock }}
            </p>


            <button class="btn btn-warning btn-lg">

                🛒 Agregar al carrito

            </button>

        </div>

    </div>

</div>

@endsection