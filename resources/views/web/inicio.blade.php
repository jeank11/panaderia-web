
@extends('layouts.web')

@section('titulo', 'Inicio')

@section('contenido')


<!-- ===================================================== -->
<!-- HERO -->
<!-- ===================================================== -->

<section class="hero">

    <div class="container text-center">

        <h1 class="display-3 fw-bold">

            🥖 PanaEcheveste

        </h1>


        <p class="lead mt-4">

            El sabor del pan artesanal, elaborado con ingredientes
            frescos y con la tradición de nuestra familia.

        </p>


        <div class="mt-5">

            <a
                href="#productos"
                class="btn btn-warning btn-lg me-3">

                <i class="bi bi-bag"></i>

                Ver Productos

            </a>


            <a
                href="/portal/login"
                class="btn btn-outline-light btn-lg me-3">

                <i class="bi bi-person-circle"></i>

                Iniciar Sesión

            </a>


            <a
                href="{{ route('clientes.registro') }}"
                class="btn btn-light btn-lg">

                <i class="bi bi-person-plus"></i>

                Crear Cuenta

            </a>

        </div>

    </div>

</section>



<!-- ===================================================== -->
<!-- PRODUCTOS -->
<!-- ===================================================== -->

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

                                <strong>

                                    {{ $producto->codigo }}

                                </strong>

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

                            <a
                                href="{{ route('producto.detalle',$producto) }}"
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



<!-- ===================================================== -->
<!-- NOSOTROS -->
<!-- ===================================================== -->

<section
    id="nosotros"
    class="seccion-nosotros py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                ❤️ Nosotros

            </h2>

            <p class="text-muted">

                Una historia familiar que comenzó hace más de 30 años.

            </p>

        </div>


        <div class="row align-items-center">


            <div class="col-lg-6 mb-4">

                <div class="card info-card p-4">

                    <div class="card-body">

                        <h3 class="fw-bold mb-4">

                            🥖 Una tradición familiar

                        </h3>


                        <p class="lead">

                            Somos una panadería familiar de
                            <strong>Piedras Coloradas</strong>,
                            con más de <strong>30 años de trayectoria</strong>
                            en el rubro.

                        </p>


                        <p>

                            Durante todos estos años hemos trabajado
                            con dedicación para llevar a nuestras familias
                            productos elaborados con la calidad,
                            el sabor y la tradición que nos caracteriza.

                        </p>


                        <p>

                            Nuestro trabajo forma parte de la vida de
                            muchas familias de Piedras Coloradas y de
                            distintos pueblos de los alrededores.

                        </p>


                    </div>

                </div>

            </div>


            <div class="col-lg-6 mb-4">

                <div class="row">


                    <div class="col-md-6 mb-4">

                        <div class="card info-card text-center p-4">

                            <div class="card-body">

                                <i class="bi bi-award info-icon"></i>

                                <h5 class="mt-3">

                                    Más de 30 años

                                </h5>

                                <p class="text-muted">

                                    De experiencia y dedicación
                                    en el rubro.

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 mb-4">

                        <div class="card info-card text-center p-4">

                            <div class="card-body">

                                <i class="bi bi-house-heart info-icon"></i>

                                <h5 class="mt-3">

                                    Panadería familiar

                                </h5>

                                <p class="text-muted">

                                    Una tradición que continúa
                                    generación tras generación.

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 mb-4">

                        <div class="card info-card text-center p-4">

                            <div class="card-body">

                                <i class="bi bi-geo-alt info-icon"></i>

                                <h5 class="mt-3">

                                    Piedras Coloradas

                                </h5>

                                <p class="text-muted">

                                    Nuestra panadería está ubicada
                                    en el departamento de Paysandú.

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 mb-4">

                        <div class="card info-card text-center p-4">

                            <div class="card-body">

                                <i class="bi bi-truck info-icon"></i>

                                <h5 class="mt-3">

                                    Llegamos a tu pueblo

                                </h5>

                                <p class="text-muted">

                                    Llevamos nuestros productos
                                    a pueblos de los alrededores.

                                </p>

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>

</section>



<!-- ===================================================== -->
<!-- CONTACTO -->
<!-- ===================================================== -->

<section
    id="contacto"
    class="seccion-contacto py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                📞 Contacto

            </h2>

            <p class="text-muted">

                Estamos para atenderte durante todo el día.

            </p>

        </div>


        <div class="row justify-content-center">


            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card info-card text-center p-4">

                    <div class="card-body">

                        <i class="bi bi-geo-alt info-icon"></i>

                        <h4 class="mt-3">

                            Nuestra ubicación

                        </h4>

                        <p>

                            📍 Piedras Coloradas

                            <br>

                            Departamento de Paysandú

                            <br>

                            Uruguay

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card info-card text-center p-4">

                    <div class="card-body">

                        <i class="bi bi-clock info-icon"></i>

                        <h4 class="mt-3">

                            Horarios

                        </h4>

                        <p>

                            Nuestro horario de atención
                            se extiende a lo largo del día.

                        </p>

                        <p class="text-muted mb-0">

                            Consultanos por disponibilidad
                            y entregas en tu localidad.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card info-card text-center p-4">

                    <div class="card-body">

                        <i class="bi bi-whatsapp info-icon"></i>

                        <h4 class="mt-3">

                            WhatsApp

                        </h4>

                        <p>

                            Comunicate directamente
                            con nosotros.

                        </p>


                        <a
                            href="https://wa.me/59898402862"
                            target="_blank"
                            class="btn whatsapp-btn btn-lg">

                            <i class="bi bi-whatsapp"></i>

                            098 402 862

                        </a>

                    </div>

                </div>

            </div>


        </div>


        <div class="text-center mt-4">

            <p class="lead">

                🥖 Desde Piedras Coloradas,
                llevando nuestros productos a las familias
                de los pueblos de los alrededores.

            </p>

        </div>

    </div>

</section>



<!-- ===================================================== -->
<!-- DESTACADOS -->
<!-- ===================================================== -->

<section class="bg-white py-5">

    <div class="container">

        <div class="row text-center">


            <div class="col-md-4 mb-4">

                <i class="bi bi-award fs-1 text-warning"></i>

                <h4 class="mt-3">

                    Calidad Garantizada

                </h4>

                <p>

                    Elaboramos nuestros productos con
                    ingredientes frescos y seleccionados.

                </p>

            </div>


            <div class="col-md-4 mb-4">

                <i class="bi bi-heart-fill fs-1 text-danger"></i>

                <h4 class="mt-3">

                    Más de 30 años

                </h4>

                <p>

                    Una tradición familiar que nos
                    acompaña desde hace más de tres décadas.

                </p>

            </div>


            <div class="col-md-4 mb-4">

                <i class="bi bi-truck fs-1 text-success"></i>

                <h4 class="mt-3">

                    Atención Personalizada

                </h4>

                <p>

                    Llevamos nuestros productos a
                    Piedras Coloradas y pueblos cercanos.

                </p>

            </div>


        </div>

    </div>

</section>


@endsection

