<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">

        <span class="navbar-brand fw-bold">
            🥖 PanaEcheveste
        </span>

        <div class="ms-auto">

            @auth
                <span class="me-3">
                    👤 {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">
                        Salir
                    </button>
                </form>
            @endauth

        </div>

    </div>
</nav>