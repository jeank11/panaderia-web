@extends('layouts.cliente')

@section('contenido')

<h2 class="mb-4">
    🛍 Comprar productos
</h2>
<div class="row mb-4">

    <div class="col-md-6">

        <input
            type="text"
            id="buscarProducto"
            class="form-control"
            placeholder="🔍 Buscar producto...">

    </div>

</div>

<div class="row">

@forelse($productos as $producto)

<div
    class="col-md-4 mb-4 producto-card"
    data-nombre="{{ strtolower($producto->nombre) }}">

    <div class="card h-100 shadow-sm">

        @if($producto->imagen)

            <img
                src="{{ asset('storage/'.$producto->imagen) }}"
                class="card-img-top"
                style="height:220px; object-fit:cover;">

        @else

            <div
                class="d-flex justify-content-center align-items-center bg-light"
                style="height:220px;font-size:70px;">

                🥖

            </div>

        @endif

        <div class="card-body">

            <h5 class="card-title">

                {{ $producto->nombre }}

            </h5>

            <p class="text-muted">

                {{ $producto->categoria->nombre }}

            </p>

            <h4 class="text-success">

                ${{ number_format($producto->precio_venta,2) }}

            </h4>

            <p>

                Stock disponible:

                <strong>

                    {{ $producto->stock }}

                </strong>

            </p>

            <form
                action="{{ route('carrito.agregar',$producto) }}"
                method="POST">

                @csrf

                <div class="input-group">

                    <input
                        type="number"
                        name="cantidad"
                        value="1"
                        min="1"
                        max="{{ $producto->stock }}"
                        class="form-control">

                    <button
                        class="btn btn-success">

                        🛒 Agregar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@empty

<div class="col-12">

    <div class="alert alert-warning">

        No hay productos disponibles.

    </div>

</div>

@endforelse

</div>
@push('scripts')

<script>

document
.getElementById('buscarProducto')
.addEventListener('keyup', function(){

    let texto = this.value.toLowerCase();

    let productos = document.querySelectorAll('.producto-card');

    productos.forEach(function(producto){

        let nombre = producto.dataset.nombre;

        if(nombre.includes(texto))
        {
            producto.style.display = '';
        }
        else
        {
            producto.style.display = 'none';
        }

    });

});

</script>

@endpush

@endsection