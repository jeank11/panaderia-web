@extends('layouts.cliente')

@section('contenido')

<h2 class="mb-4">
    📒 Estado de cuenta
</h2>


<div class="card shadow-sm mb-4">

    <div class="card-body">

        <h4>
            {{ $cliente->nombre }} {{ $cliente->apellido }}
        </h4>


        <hr>


        <h5>
            Deuda actual:
        </h5>


        <h2 class="text-danger">
            ${{ number_format($deuda,2) }}
        </h2>


    </div>

</div>

<div class="card shadow-sm mb-4">

<div class="card-body">

<h4 class="mb-3">
    🛒 Compras fiadas
</h4>


@forelse($ventas as $venta)


<div class="card mb-3 border">


<div class="card-header bg-light">

<strong>
Fecha de compra:
</strong>

{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}


</div>



<div class="card-body">


<table class="table table-sm">


<thead>

<tr>

<th>
Producto
</th>

<th>
Cantidad
</th>

</tr>

</thead>


<tbody>


@foreach($venta->detalles as $detalle)

<tr>

<td>
{{ $detalle->producto->nombre }}
</td>


<td>
{{ $detalle->cantidad }}
</td>


</tr>


@endforeach


</tbody>


</table>



<hr>


<div class="row">


<div class="col-md-6">

<strong>
Total compra:
</strong>

${{ number_format($venta->total,2) }}

</div>



<div class="col-md-6 text-danger">

<strong>
Saldo pendiente:
</strong>

${{ number_format($venta->saldo_pendiente,2) }}

</div>


</div>


</div>


</div>


@empty


<div class="alert alert-success">

No tiene compras fiadas.

</div>


@endforelse


</div>

</div>

<div class="card shadow-sm mt-4">

<div class="card-body">

<h4>
    💰 Pagos realizados
</h4>


<table class="table table-bordered">


<thead class="table-dark">

<tr>

<th>
Fecha
</th>

<th>
Monto
</th>

<th>
Observación
</th>

</tr>

</thead>


<tbody>


@forelse($pagos as $pago)

<tr>

<td>
{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}
</td>


<td>
${{ number_format($pago->monto,2) }}
</td>


<td>
{{ $pago->observacion }}
</td>


</tr>


@empty


<tr>

<td colspan="3" class="text-center">

No tiene pagos registrados.

</td>

</tr>


@endforelse


</tbody>


</table>


</div>

</div>


@endsection