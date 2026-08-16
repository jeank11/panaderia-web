<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Producción - {{ $fecha }}
    </title>

    <style>

        body {

            font-family: Arial, sans-serif;

            margin: 40px;

            color: #000;

        }


        .encabezado {

            text-align: center;

            margin-bottom: 30px;

        }


        h1 {

            margin-bottom: 5px;

        }


        .fecha {

            font-size: 18px;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 25px;

        }


        th,
        td {

            border: 1px solid #000;

            padding: 12px;

        }


        th {

            text-align: left;

        }


        .cantidad {

            text-align: center;

            width: 180px;

        }


        .total {

            margin-top: 25px;

            text-align: right;

            font-size: 20px;

            font-weight: bold;

        }


        .pedidos {

            margin-top: 10px;

            font-size: 14px;

        }


        .acciones {

            margin-bottom: 25px;

        }


        @media print {

            .acciones {

                display: none;

            }

        }

    </style>

</head>


<body>


    <div class="acciones">

        <button onclick="window.print()">

            🖨️ Imprimir

        </button>

    </div>


    <div class="encabezado">

        <h1>

            LISTA DE PRODUCCIÓN

        </h1>


        <div class="fecha">

            Fecha:

            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}

        </div>


        <div class="pedidos">

            Pedidos pendientes:

            {{ $pedidos->count() }}

        </div>

    </div>


    @if(count($produccion))


        <table>

            <thead>

                <tr>

                    <th>

                        Producto

                    </th>


                    <th class="cantidad">

                        Cantidad

                    </th>

                </tr>

            </thead>


            <tbody>


                @foreach($produccion as $item)


                    <tr>

                        <td>

                            {{ $item['producto']->nombre }}

                        </td>


                        <td class="cantidad">

                            {{ $item['cantidad'] }}

                        </td>

                    </tr>


                @endforeach


            </tbody>

        </table>


        <div class="total">

            Total de unidades:

            {{ collect($produccion)->sum('cantidad') }}

        </div>


    @else


        <p>

            No hay pedidos pendientes de producción para esta fecha.

        </p>


    @endif


</body>

</html>