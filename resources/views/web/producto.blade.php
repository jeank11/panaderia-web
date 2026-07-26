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

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif
            @if(session('error'))

<div class="alert alert-danger">

    {{ session('error') }}

</div>

@endif

            <h1>

                {{ $producto->nombre }}

            </h1>

            <p>

                <strong>Código:</strong>

                {{ $producto->codigo }}

            </p>

            <h3 class="text-success">

                ${{ number_format($producto->precio_venta,2) }}

            </h3>

            <p>

                <strong>Stock disponible:</strong>

                {{ $producto->stock }}

            </p>

            <form
                action="{{ route('carrito.agregar', $producto) }}"
                method="POST">

                @csrf

                <button
                    type="submit"
                    class="btn btn-warning btn-lg">

                    🛒 Agregar al carrito

                </button>

            </form>

        </div>

    </div>

</div>

@endsection