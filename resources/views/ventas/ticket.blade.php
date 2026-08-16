<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Ticket Venta #{{ $venta->id }}</title>

<style>

body{
    font-family:Arial, Helvetica, sans-serif;
    width:320px;
    margin:auto;
    font-size:13px;
    color:#000;
}

h2,h3,p{
    margin:0;
}

.center{
    text-align:center;
}

.right{
    text-align:right;
}

.linea{
    border-top:1px dashed #000;
    margin:10px 0;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    text-align:left;
    border-bottom:1px solid #000;
    padding-bottom:4px;
}

td{
    padding:4px 0;
    vertical-align:top;
}

.total{
    font-size:18px;
    font-weight:bold;
}

.info p{
    margin:2px 0;
}

@media print{

    button{
        display:none;
    }

}

</style>

</head>

<body>

<div class="center">

<h2>🥖 PanaEcheveste</h2>

<p>Panadería Artesanal</p>

<p>Ticket de Venta</p>

</div>

<div class="linea"></div>

<div class="info">

<p><strong>Ticket:</strong> {{ str_pad($venta->id,6,'0',STR_PAD_LEFT) }}</p>

<p><strong>Fecha:</strong>
{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}
</p>

<p><strong>Cliente:</strong>
{{ $venta->cliente->nombre_completo }}
</p>

@if($venta->cliente->documento)

<p><strong>Documento:</strong>
{{ $venta->cliente->documento }}
</p>

@endif

<p><strong>Vendedor:</strong>
{{ $venta->usuario->name }}
</p>

<p><strong>Pago:</strong>
{{ ucfirst($venta->tipo_pago) }}
</p>

<p><strong>Estado:</strong>
{{ ucfirst($venta->estado_pago) }}
</p>

</div>

<div class="linea"></div>

<table>

<thead>

<tr>

<th>Producto</th>

<th class="right">Cant.</th>

<th class="right">Subt.</th>

</tr>

</thead>

<tbody>

@foreach($venta->detalles as $detalle)

<tr>

<td>

{{ $detalle->producto->nombre }}

<br>

<small>

$ {{ number_format($detalle->precio,2) }} c/u

</small>

</td>

<td class="right">

{{ $detalle->cantidad }}

</td>

<td class="right">

${{ number_format($detalle->subtotal,2) }}

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="linea"></div>

<table>

<tr>

<td><strong>Total</strong></td>

<td class="right total">

${{ number_format($venta->total,2) }}

</td>

</tr>

@if($venta->saldo_pendiente > 0)

<tr>

<td>

<strong>Saldo pendiente</strong>

</td>

<td class="right">

${{ number_format($venta->saldo_pendiente,2) }}

</td>

</tr>

@endif

</table>

<div class="linea"></div>

<div class="center">

<p>

¡Gracias por elegirnos!

</p>

<p>

Lo esperamos nuevamente 😊

</p>

</div>

<br>

<div class="center">

<button onclick="window.print()">

🖨 Imprimir

</button>

</div>

</body>

</html>