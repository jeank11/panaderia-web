<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>@yield('titulo','PanaEcheveste')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        html{
            scroll-behavior:smooth;
        }

        body{
            background:#FFF8F0;
            font-family:Arial, Helvetica, sans-serif;
        }

        .navbar{
            background:#8B4513;
        }

        .navbar-brand{
            font-size:28px;
            font-weight:bold;
            color:white !important;
        }

        .nav-link{
            color:white !important;
            font-weight:500;
        }

        .nav-link:hover{
            color:#FFD54F !important;
        }

        .hero{
            padding:100px 0;

            background:
                linear-gradient(
                    rgba(0,0,0,.45),
                    rgba(0,0,0,.45)
                ),
                url('https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1600&q=80');

            background-size:cover;
            background-position:center;

            color:white;
        }

        .seccion-nosotros{
            background:#FFF8F0;
        }

        .seccion-contacto{
            background:white;
        }

        .info-card{
            border:none;
            border-radius:15px;

            box-shadow:
                0 5px 20px rgba(0,0,0,.08);

            height:100%;
        }

        .info-icon{
            font-size:45px;
            color:#8B4513;
        }

        .whatsapp-btn{
            background:#25D366;
            color:white;
            border:none;
        }

        .whatsapp-btn:hover{
            background:#1ebe5d;
            color:white;
        }

        footer{
            background:#8B4513;
            color:white;
            padding:25px;
            margin-top:60px;
        }

    </style>

</head>


<body>


<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand" href="/">

            🥖 PanaEcheveste

        </a>


        <button
            class="navbar-toggler bg-light"
            data-bs-toggle="collapse"
            data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="menu">

            <ul class="navbar-nav ms-auto">


                <li class="nav-item">

                    <a class="nav-link" href="/">

                        Inicio

                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="#productos">

                        Productos

                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="#nosotros">

                        Nosotros

                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="#contacto">

                        Contacto

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="{{ route('carrito.index') }}">

                        🛒 Carrito

                        <span class="badge bg-warning text-dark">

                            {{ count(session('carrito', [])) }}

                        </span>

                    </a>

                </li>


                @if(session()->has('cliente_id'))

                    <li class="nav-item dropdown">

                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            👤 {{ $clientePortal->nombre }}

                        </a>


                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route('clientes.perfil') }}">

                                    👤 Mi Perfil

                                </a>

                            </li>


                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route('clientes.compras') }}">

                                    📦 Mis Pedidos

                                </a>

                            </li>


                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route('carrito.index') }}">

                                    🛒 Mi Carrito

                                </a>

                            </li>


                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route('clientes.password.form') }}">

                                    🔑 Cambiar Contraseña

                                </a>

                            </li>


                            <li>

                                <hr class="dropdown-divider">

                            </li>


                            <li>

                                <form
                                    action="{{ route('clientes.logout') }}"
                                    method="POST">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item text-danger">

                                        🚪 Cerrar sesión

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </li>

                @else

                    <li class="nav-item">

                        <a
                            class="btn btn-warning ms-3"
                            href="{{ route('clientes.login') }}">

                            Ingresar

                        </a>

                    </li>

                @endif


            </ul>

        </div>

    </div>

</nav>


@yield('contenido')


<footer>

    <div class="container text-center">

        <h5>

            🥖 PanaEcheveste

        </h5>

        <p>

            Panadería familiar de Piedras Coloradas,
            con más de 30 años de trayectoria.

        </p>

        <p class="mb-0">

            © {{ date('Y') }}

            Todos los derechos reservados.

        </p>

    </div>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

