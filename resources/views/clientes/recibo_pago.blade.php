<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
Recibo de pago - PanaEcheveste
</title>


<style>

*{
    box-sizing:border-box;
}


body{

    font-family:Arial, Helvetica, sans-serif;

    margin:0;

    padding:30px;

    background:#f3f4f6;

    color:#212529;

}


.container{

    max-width:850px;

    margin:auto;

}


.recibo{

    background:white;

    border-radius:14px;

    box-shadow:0 4px 20px rgba(0,0,0,.10);

    overflow:hidden;

}


.header{

    background:#198754;

    color:white;

    padding:30px;

    text-align:center;

}


.header h1{

    margin:0 0 8px;

    font-size:32px;

}


.header h2{

    margin:0;

    font-size:20px;

    font-weight:normal;

}


.contenido{

    padding:35px;

}


.info{

    display:grid;

    grid-template-columns:repeat(3,1fr);

    gap:15px;

    margin-bottom:25px;

}


.info-box{

    background:#f8f9fa;

    border-radius:10px;

    padding:15px;

    border:1px solid #e5e7eb;

}


.info-box small{

    display:block;

    color:#6c757d;

    margin-bottom:5px;

}


.info-box strong{

    font-size:16px;

}


.separador{

    border-top:1px dashed #adb5bd;

    margin:25px 0;

}


.titulo{

    font-size:20px;

    font-weight:bold;

    margin-bottom:20px;

}


.compra{

    border:1px solid #dee2e6;

    border-radius:10px;

    overflow:hidden;

    margin-bottom:25px;

}


.compra-header{

    background:#f8f9fa;

    padding:15px 18px;

    border-bottom:1px solid #dee2e6;

}


.compra-header h4{

    margin:0 0 5px;

}


.compra-header small{

    color:#6c757d;

}


table{

    width:100%;

    border-collapse:collapse;

}


th{

    background:#212529;

    color:white;

    padding:11px;

    text-align:left;

    font-size:14px;

}


td{

    padding:11px;

    border-bottom:1px solid #eeeeee;

}


td.cantidad{

    text-align:center;

}


td.precio{

    text-align:right;

}


.total-compra{

    background:#f8f9fa;

    font-weight:bold;

}


.total-final{

    background:#198754;

    color:white;

    border-radius:12px;

    padding:22px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-top:30px;

}


.total-final span{

    font-size:18px;

}


.total-final strong{

    font-size:28px;

}


.gracias{

    text-align:center;

    color:#6c757d;

    margin-top:30px;

}


.acciones{

    display:flex;

    justify-content:center;

    gap:12px;

    margin-top:25px;

}


.btn{

    display:inline-block;

    padding:11px 20px;

    border-radius:8px;

    text-decoration:none;

    border:none;

    cursor:pointer;

    font-size:15px;

    font-weight:bold;

}


.btn-imprimir{

    background:#0d6efd;

    color:white;

}


.btn-volver{

    background:#6c757d;

    color:white;

}


@media(max-width:700px){

    body{

        padding:10px;

    }


    .contenido{

        padding:20px;

    }


    .info{

        grid-template-columns:1fr;

    }


    .total-final{

        flex-direction:column;

        gap:8px;

        text-align:center;

    }


    .acciones{

        flex-direction:column;

    }


    .btn{

        width:100%;

        text-align:center;

    }


    table{

        font-size:13px;

    }


    th,
    td{

        padding:8px;

    }

}


@media print{

    body{

        background:white;

        padding:0;

    }


    .recibo{

        box-shadow:none;

        border-radius:0;

    }


    .acciones{

        display:none;

    }


    .header{

        background:#198754 !important;

        -webkit-print-color-adjust:exact;

        print-color-adjust:exact;

    }


    .total-final{

        background:#198754 !important;

        -webkit-print-color-adjust:exact;

        print-color-adjust:exact;

    }


    th{

        background:#212529 !important;

        color:white !important;

        -webkit-print-color-adjust:exact;

        print-color-adjust:exact;

    }


}


</style>

</head>


<body>


<div class="container">


<div class="recibo">


{{-- ============================================================
     ENCABEZADO
============================================================ --}}

<div class="header">

<h1>
🥖 PanaEcheveste
</h1>

<h2>
Recibo de cancelación de cuenta corriente
</h2>

</div>



<div class="contenido">


{{-- ============================================================
     INFORMACIÓN DEL CLIENTE
============================================================ --}}

<div class="info">


<div class="info-box">

<small>
Cliente
</small>

<strong>
{{ $cliente->nombre_completo }}
</strong>

</div>


<div class="info-box">

<small>
Documento
</small>

<strong>
{{ $cliente->documento }}
</strong>

</div>


<div class="info-box">

<small>
Fecha de pago
</small>

<strong>
{{ date('d/m/Y H:i') }}
</strong>

</div>


</div>



<div class="separador"></div>



{{-- ============================================================
     DETALLE
============================================================ --}}

<div class="titulo">

🧾 Detalle de compras canceladas

</div>



@foreach($ventas as $venta)


<div class="compra">


<div class="compra-header">

<h4>

Compra #{{ $venta->id }}

</h4>


<small>

📅

{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}

</small>

</div>



<table>


<thead>

<tr>

<th>
Producto
</th>

<th>
Cantidad
</th>

<th style="text-align:right">
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


<td class="cantidad">

{{ $detalle->cantidad }}

</td>


<td class="precio">

${{ number_format($detalle->subtotal,2) }}

</td>

</tr>


@endforeach



<tr class="total-compra">

<td colspan="2">

Total de la compra

</td>

<td class="precio">

${{ number_format($venta->total,2) }}

</td>

</tr>


</tbody>


</table>



</div>


@endforeach



{{-- ============================================================
     TOTAL
============================================================ --}}

<div class="total-final">

<span>
💰 TOTAL PAGADO
</span>

<strong>
${{ number_format($totalPagado,2) }}
</strong>

</div>



<div class="gracias">

<p>
Gracias por su pago.
</p>

<p>
🥖 ¡Vuelva pronto!
</p>

</div>



{{-- ============================================================
     BOTONES
============================================================ --}}

<div class="acciones">


<button
    type="button"
    class="btn btn-imprimir"
    onclick="window.print()">

    🖨️ Imprimir recibo

</button>


<a
    href="{{ route('clientes.cuenta', $cliente) }}"
    class="btn btn-volver">

    ↩️ Volver a la cuenta corriente

</a>


</div>


</div>


</div>


</div>


</body>

</html>