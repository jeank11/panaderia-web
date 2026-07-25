@extends('layouts.web')

@section('titulo', 'Inicio')

@section('contenido')

<section class="hero">

    <div class="container text-center">

        <h1 class="display-3 fw-bold">

            🥖 PanaEcheveste

        </h1>

        <p class="lead mt-4">

            El sabor del pan artesanal, elaborado con ingredientes frescos todos los días.

        </p>

        <div class="mt-5">

            <a href="#productos" class="btn btn-warning btn-lg me-3">

                <i class="bi bi-bag"></i>

                Ver Productos

            </a>

            <a href="/portal/login" class="btn btn-outline-light btn-lg">

                <i class="bi bi-person-circle"></i>

                Iniciar Sesión

            </a>

        </div>

    </div>

</section>

<section class="py-5" id="productos">

    <div class="container">

        <h2 class="text-center mb-5">

            Nuestros Productos

        </h2>

        <div class="row">

            @forelse($productos as $producto)

                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="card shadow h-100">

                        @if($producto->imagen)

                            <img
                                src="{{ asset('storage/'.$producto->imagen) }}"
                                class="card-img-top"
                                style="height:220px; object-fit:cover;">

                        @else

                            <div
                                class="d-flex align-items-center justify-content-center"
                                style="height:220px; background:#f2f2f2;">

                                <i
                                    class="bi bi-image"
                                    style="font-size:70px; color:#999;">
                                </i>

                            </div>

                        @endif

                        <div class="card-body">

                            <h4>

                                {{ $producto->nombre }}

                            </h4>

                            <p class="text-muted mb-2">

                                Código:
                                <strong>{{ $producto->codigo }}</strong>

                            </p>

                            <h5 class="text-success">

                                ${{ number_format($producto->precio_venta,2) }}

                            </h5>

                            <p class="mt-3">

                                Stock disponible:

                                <strong>

                                    {{ $producto->stock }}

                                </strong>

                            </p>

                        </div>

                        <div class="card-footer bg-white border-0">

    <a href="{{ route('producto.detalle',$producto) }}"
       class="btn btn-warning w-100">

        <i class="bi bi-eye"></i>

        Ver Producto

    </a>

</div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-warning text-center">

                        No existen productos registrados.

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</section>

<section class="bg-white py-5">

    <div class="container">

        <div class="row text-center">

            <div class="col-md-4 mb-4">

                <i class="bi bi-award fs-1 text-warning"></i>

                <h4 class="mt-3">

                    Calidad Garantizada

                </h4>

                <p>

                    Elaboramos nuestros productos con ingredientes frescos y seleccionados.

                </p>

            </div>

            <div class="col-md-4 mb-4">

                <i class="bi bi-heart-fill fs-1 text-danger"></i>

                <h4 class="mt-3">

                    Tradición

                </h4>

                <p>

                    Más de 20 años llevando el mejor pan artesanal a nuestros clientes.

                </p>

            </div>

            <div class="col-md-4 mb-4">

                <i class="bi bi-truck fs-1 text-success"></i>

                <h4 class="mt-3">

                    Atención Personalizada

                </h4>

                <p>

                    Nos preocupamos por brindar el mejor servicio todos los días.

                </p>

            </div>

        </div>

    </div>

</section>

@endsection