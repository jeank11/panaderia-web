<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Ticket Venta #{{ $venta->id }}</title>


    <style>

        body{
            font-family: Arial, sans-serif;
            width: 300px;
            margin: auto;
            font-size: 14px;
        }


        .text-center{
            text-align:center;
        }


        .linea{
            border-top:1px dashed #000;
            margin:10px 0;
        }


        table{
            width:100%;
            border-collapse:collapse;
        }


        td{
            padding:5px 0;
        }


        .total{

            font-size:18px;
            font-weight:bold;
            text-align:right;

        }


        @media print {

            button{
                display:none;
            }

        }

    </style>

</head>


<body>


<div class="text-center">

    <h2>
        🥖 PanaEcheveste
    </h2>

    <p>
        Panadería Artesanal
    </p>


</div>


<div class="linea"></div>


<p>
<strong>Venta Nº:</strong>
{{ $venta->id }}
</p>


<p>
<strong>Fecha:</strong>
{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}
</p>


<p>
<strong>Cliente:</strong>
{{ $venta->cliente->nombre_completo }}
</p>


<p>
<strong>Atendido por:</strong>
{{ $venta->usuario->name }}
</p>


<div class="linea"></div>


<table>


@foreach($venta->detalles as $detalle)

<tr>

    <td>
        {{ $detalle->producto->nombre }}
    </td>


    <td style="text-align:right">

        {{ $detalle->cantidad }}

    </td>


</tr>


<tr>

    <td>
        ${{ number_format($detalle->precio,2) }}
    </td>


    <td style="text-align:right">

        ${{ number_format($detalle->subtotal,2) }}

    </td>


</tr>


@endforeach


</table>


<div class="linea"></div>


<p class="total">

TOTAL:
${{ number_format($venta->total,2) }}

</p>


<div class="linea"></div>


<div class="text-center">

<p>
¡Gracias por su compra!
</p>

<p>
Vuelva pronto 😊
</p>


</div>


<br>


<div class="text-center">

<button onclick="window.print()">

    🖨 Imprimir

</button>


</div>


</body>

</html>