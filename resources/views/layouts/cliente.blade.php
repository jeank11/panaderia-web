<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Portal del Cliente | PanaEcheveste</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#f5f5f5;
        }

        .navbar{
            background:#8B4513;
        }

        .navbar-brand{
            font-weight:bold;
            color:white !important;
        }

        .nav-link{
            color:white !important;
        }

        .nav-link:hover{
            color:#ffd966 !important;
        }

        .card{
            border:none;
            border-radius:12px;
        }

        footer{
            margin-top:40px;
            padding:20px;
            text-align:center;
            color:#777;
        }

    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg shadow">

    <div class="container">

  <a class="navbar-brand"
   href="{{ route('clientes.productos') }}">

    🥖 PanaEcheveste

</a>

        <button class="navbar-toggler bg-light"
                data-bs-toggle="collapse"
                data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item dropdown">

    <a
        class="nav-link dropdown-toggle"
        href="#"
        role="button"
        data-bs-toggle="dropdown">

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
                href="{{ route('clientes.pedidos') }}">

                📦 Mis Pedidos

            </a>

        </li>
         <li>

           <a
               class="dropdown-item"
               href="{{ route('clientes.compras') }}">

               📜 Mis Compras

          </a>

        </li>
        <li>

    <a
        class="dropdown-item"
        href="{{ route('clientes.estado_cuenta') }}">

        📒 Estado de cuenta

    </a>

</li>

        <li>

    <a
        class="dropdown-item"
        href="{{ route('clientes.productos') }}">

        🛍 Comprar

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

        <li><hr class="dropdown-divider"></li>

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

            </ul>

        </div>

    </div>

</nav>

<div class="container mt-4">

    @yield('contenido')

</div>

<footer>

    © {{ date('Y') }}

    PanaEcheveste

    - Portal del Cliente

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>

</html>