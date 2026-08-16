<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>
Recibo de pago
</title>


<style>

body{

    font-family: Arial, sans-serif;
    margin:40px;

}


.container{

    width:700px;
    margin:auto;

}


.text-center{

    text-align:center;

}


.linea{

    border-top:1px dashed #000;
    margin:20px 0;

}


table{

    width:100%;
    border-collapse:collapse;
    margin-bottom:15px;

}


th{

    background:#eee;
    padding:10px;
    border:1px solid #ccc;

}


td{

    padding:10px;
    border:1px solid #ccc;

}


.total{

    font-size:22px;
    font-weight:bold;
    text-align:right;
    margin-top:20px;

}


.info{

    margin-bottom:20px;

}


.compra{

    margin-top:25px;

}


button{

    padding:10px 20px;
    font-size:16px;

}


@media print{

    button{

        display:none;

    }

}


</style>


</head>


<body>


<div class="container">


<div class="text-center">


<h1>
🥖 PanaEcheveste
</h1>


<h2>
Recibo de cancelación de cuenta corriente
</h2>


</div>



<div class="linea"></div>



<div class="info">


<p>
<strong>Cliente:</strong>
{{ $cliente->nombre_completo }}
</p>


<p>
<strong>Documento:</strong>
{{ $cliente->documento }}
</p>


<p>
<strong>Fecha de pago:</strong>
{{ date('d/m/Y H:i') }}
</p>


</div>



<div class="linea"></div>



<h3>
Detalle de compras canceladas
</h3>



@php

$totalGeneral = 0;

@endphp



@foreach($ventas as $venta)


<div class="compra">


<h4>

Compra realizada:
{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}

</h4>


<p>

<strong>
Venta Nº:
</strong>

{{ $venta->id }}

</p>



<table>


<thead>

<tr>

<th>
Producto
</th>

<th>
Cantidad
</th>

<th>
Subtotal
</th>

</tr>

</thead>



<tbody>



@foreach($venta->detalles as $detalle)


<tr>


<td>

{{ $detalle->producto->nombre }}

</td>



<td style="text-align:center">

{{ $detalle->cantidad }}

</td>



<td>

${{ number_format($detalle->subtotal,2) }}

</td>


</tr>



@endforeach



<tr>


<td colspan="2">

<strong>
Total compra
</strong>

</td>


<td>

<strong>
${{ number_format($venta->total,2) }}

</strong>

</td>


</tr>



</tbody>


</table>



@php

$totalGeneral += $venta->total;

@endphp



</div>



@endforeach




<div class="linea"></div>



<p class="total">

TOTAL PAGADO:

${{ number_format($totalGeneral,2) }}

</p>




<div class="linea"></div>



<div class="text-center">


<p>
Gracias por su pago.
</p>


<p>
🥖 Vuelva pronto
</p>


<br>


<button onclick="window.print()">

🖨 Imprimir recibo

</button>


</div>



</div>


</body>


</html>