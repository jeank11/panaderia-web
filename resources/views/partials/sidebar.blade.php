<div class="sidebar p-3">

    <h4 class="text-white mb-4">
        🥖 PanaEcheveste
    </h4>


    {{-- MENÚ PRINCIPAL --}}

    <a href="{{ route('dashboard') }}">
        🏠 Dashboard
    </a>


    <a href="{{ route('categorias.index') }}">
        📂 Categorías
    </a>


    <a href="{{ route('productos.index') }}">
        🥖 Productos
    </a>


    <a href="{{ route('clientes.index') }}">
        👥 Clientes
    </a>


    <a href="#">
        🚚 Proveedores
    </a>


    <a href="{{ route('pedidos.index') }}">
        📦 Pedidos
    </a>


    <a href="{{ route('ventas.index') }}">
        🛒 Ventas
    </a>

    <a href="{{ route('transferencias.index') }}">
    🏦 Transferencias
    </a>


    {{-- MENÚ ADMINISTRADOR --}}

    @auth

        @if(auth()->user()->role === 'administrador')

            <hr class="border-light my-3">

            <div class="text-white small mb-2">
                ⚙️ ADMINISTRACIÓN
            </div>


            <a href="{{ route('administradores.index') }}">
                👤 Administradores
            </a>


        @endif

    @endauth

</div>