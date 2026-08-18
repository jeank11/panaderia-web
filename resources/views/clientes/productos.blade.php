@extends('layouts.cliente')

@section('contenido')

<div class="container py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

        <div>

            <h2 class="fw-bold mb-1">
                🛍️ Comprar productos
            </h2>

            <p class="text-muted mb-0">
                Elegí tus productos y agregalos al carrito.
            </p>

        </div>

        <a
            href="{{ route('carrito.index') }}"
            class="btn btn-success btn-lg">

            🛒 Mi Carrito

            <span class="badge bg-light text-success ms-1">
                {{ count(session('carrito', [])) }}
            </span>

        </a>

    </div>


    {{-- MENSAJES --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- BUSCADOR Y FILTRO --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-8">

                    <label class="form-label fw-bold">

                        🔍 Buscar producto

                    </label>

                    <input
                        type="text"
                        id="buscarProducto"
                        class="form-control form-control-lg"
                        placeholder="Ej: pan francés, bizcochos, pizza...">

                </div>


                <div class="col-md-4">

                    <label class="form-label fw-bold">

                        📂 Categoría

                    </label>

                    <select
                        id="filtroCategoria"
                        class="form-select form-select-lg">

                        <option value="">
                            Todas las categorías
                        </option>

                        @foreach($productos->pluck('categoria')->filter()->unique('id') as $categoria)

                            <option value="{{ strtolower($categoria->nombre) }}">

                                {{ $categoria->nombre }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- PRODUCTOS --}}

    <div class="row g-4" id="listaProductos">

        @forelse($productos as $producto)

            <div
                class="col-12 col-sm-6 col-lg-4 producto-card"
                data-nombre="{{ strtolower($producto->nombre) }}"
                data-categoria="{{ strtolower($producto->categoria->nombre ?? '') }}">

                <div class="card h-100 shadow-sm border-0 producto">

                    {{-- IMAGEN --}}

                    @if($producto->imagen)

                        <img
                            src="{{ asset('storage/'.$producto->imagen) }}"
                            class="card-img-top producto-imagen"
                            alt="{{ $producto->nombre }}">

                    @else

                        <div class="producto-sin-imagen">

                            🥖

                        </div>

                    @endif


                    <div class="card-body d-flex flex-column">

                        {{-- NOMBRE --}}

                        <h4 class="card-title fw-bold">

                            {{ $producto->nombre }}

                        </h4>


                        {{-- CATEGORÍA --}}

                        @if($producto->categoria)

                            <span class="badge bg-light text-dark align-self-start mb-2">

                                {{ $producto->categoria->nombre }}

                            </span>

                        @endif


                        {{-- PRECIO --}}

                        <div class="precio mb-2">

                            ${{ number_format($producto->precio_venta, 2) }}

                        </div>


                        {{-- STOCK --}}

                        <p class="text-muted mb-3">

                            <i class="bi bi-box-seam"></i>

                            Stock disponible:

                            <strong>

                                {{ $producto->stock }}

                            </strong>

                        </p>


                        {{-- FORMULARIO --}}

                        <form
                            action="{{ route('carrito.agregar', $producto) }}"
                            method="POST"
                            class="mt-auto">

                            @csrf

                            <label class="form-label fw-bold">

                                Cantidad

                            </label>

                            <div class="input-group input-group-lg">

                                <input
                                    type="number"
                                    name="cantidad"
                                    value="1"
                                    min="1"
                                    max="{{ $producto->stock }}"
                                    class="form-control text-center"
                                    required>

                                <button
                                    type="submit"
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

                <div class="alert alert-warning text-center">

                    <h5>

                        🥖 No hay productos disponibles

                    </h5>

                    <p class="mb-0">

                        En este momento no tenemos productos disponibles para comprar.

                    </p>

                </div>

            </div>

        @endforelse

    </div>


    {{-- SIN RESULTADOS --}}

    <div
        id="sinResultados"
        class="alert alert-info text-center mt-4"
        style="display:none;">

        🔍 No encontramos productos con esa búsqueda.

    </div>

</div>


<style>

    .producto{

        border-radius:16px;

        overflow:hidden;

        transition:
            transform .2s ease,
            box-shadow .2s ease;

    }


    .producto:hover{

        transform:translateY(-5px);

        box-shadow:0 10px 25px rgba(0,0,0,.12) !important;

    }


    .producto-imagen{

        height:230px;

        object-fit:cover;

    }


    .producto-sin-imagen{

        height:230px;

        display:flex;

        justify-content:center;

        align-items:center;

        background:#f8f8f8;

        font-size:80px;

    }


    .precio{

        font-size:28px;

        font-weight:bold;

        color:#198754;

    }


    .producto .form-control{

        min-width:70px;

    }


    @media(max-width:576px){

        .producto-imagen,
        .producto-sin-imagen{

            height:200px;

        }


        .precio{

            font-size:25px;

        }


        .input-group-lg .form-control{

            min-width:65px;

        }

    }

</style>


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function(){

    const buscador =
        document.getElementById('buscarProducto');

    const categoria =
        document.getElementById('filtroCategoria');

    const productos =
        document.querySelectorAll('.producto-card');

    const sinResultados =
        document.getElementById('sinResultados');


    function filtrarProductos(){

        const texto =
            buscador.value.toLowerCase().trim();

        const categoriaSeleccionada =
            categoria.value.toLowerCase();


        let encontrados = 0;


        productos.forEach(function(producto){

            const nombre =
                producto.dataset.nombre;

            const categoriaProducto =
                producto.dataset.categoria;


            const coincideNombre =
                nombre.includes(texto);


            const coincideCategoria =
                categoriaSeleccionada === ''
                ||
                categoriaProducto === categoriaSeleccionada;


            if(
                coincideNombre
                &&
                coincideCategoria
            ){

                producto.style.display = '';

                encontrados++;

            }
            else{

                producto.style.display = 'none';

            }

        });


        if(encontrados === 0){

            sinResultados.style.display = 'block';

        }
        else{

            sinResultados.style.display = 'none';

        }

    }


    buscador.addEventListener(
        'keyup',
        filtrarProductos
    );


    categoria.addEventListener(
        'change',
        filtrarProductos
    );

});

</script>

@endpush

@endsection