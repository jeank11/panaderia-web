<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PanaEcheveste</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            margin:0;
            background:#f5f5f5;
        }

        .sidebar{
            width:250px;
            min-height:100vh;
            background:#8B4513;
        }

        .sidebar a{
            color:white;
            text-decoration:none;
            display:block;
            padding:12px 20px;
        }

        .sidebar a:hover{
            background:#6f3710;
        }

        .content{
            flex:1;
        }

    </style>

</head>

<body>

<div class="d-flex">

    {{-- Menú lateral --}}
    @include('partials.sidebar')

    <div class="content">

        {{-- Barra superior --}}
        @include('partials.navbar')

        <div class="container mt-4">

            @yield('contenido')

        </div>

    </div>

</div>

</body>

</html>